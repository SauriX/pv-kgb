/**
 * Cliente de impresora térmica vía WebSocket.
 * ES5 a propósito: el iPad 2 (Safari iOS 9) no soporta class/static
 * y un error de sintaxis impide que el script cargue.
 */
function Printer(printerName, ip) {
    this.printerName = printerName || 'Impresora Térmica';
    this.ip = (typeof ip === 'undefined' || ip === null || ip === '') ? null : ip;
    this.isConnected = false;
    this.messageHandlers = {};
    this.printList = {
        printerName: this.printerName,
        commands: []
    };
}

Printer.printServerHost = function() {
    return 'localhost';
};

Printer.configuredServerIp = function() {
    if (typeof window === 'undefined' || !window.PV_SERVER_IP) {
        return '';
    }
    var ip = String(window.PV_SERVER_IP).replace(/^\s+|\s+$/g, '');
    if (!ip || ip === 'localhost' || ip === '127.0.0.1') {
        return '';
    }
    return ip;
};

Printer.isTabletOrPhone = function() {
    if (typeof navigator === 'undefined') {
        return false;
    }
    var ua = String(navigator.userAgent || navigator.vendor || '');
    if (ua.indexOf('iPad') !== -1 || ua.indexOf('iPhone') !== -1 || ua.indexOf('iPod') !== -1) {
        return true;
    }
    if (ua.indexOf('Macintosh') !== -1 && navigator.maxTouchPoints && navigator.maxTouchPoints > 1) {
        return true;
    }
    if (/Android|webOS|BlackBerry|IEMobile|Opera Mini|Mobile|Tablet|PlayBook|Silk/i.test(ua)) {
        return true;
    }
    return false;
};

Printer.pageHost = function() {
    if (typeof window === 'undefined' || !window.location || !window.location.hostname) {
        return '';
    }
    var host = String(window.location.hostname);
    if (host === 'localhost' || host === '127.0.0.1') {
        return '';
    }
    return host;
};

Printer.printServerHosts = function(preferredIp) {
    var hosts = [];
    var seen = {};

    function addHost(host) {
        if (!host || seen[host]) {
            return;
        }
        seen[host] = true;
        hosts.push(host);
    }

    if (preferredIp) {
        addHost(preferredIp);
        return hosts;
    }

    if (Printer._shared.preferredIp) {
        addHost(Printer._shared.preferredIp);
    }

    var localHost = Printer.printServerHost();
    var serverIp = Printer.configuredServerIp();
    var pageHost = Printer.pageHost();

    if (Printer.isTabletOrPhone()) {
        addHost(serverIp);
        addHost(pageHost);
    } else {
        addHost(localHost);
        addHost(serverIp);
        addHost(pageHost);
    }

    return hosts;
};

Printer.connectToHost = function(ip) {
    if (Printer._shared.ws && Printer._shared.ws.readyState === WebSocket.OPEN && Printer._shared.ip === ip) {
        return Promise.resolve(Printer._shared.ws);
    }
    if (Printer._shared.promise && Printer._shared.ip === ip) {
        return Printer._shared.promise;
    }

    Printer._shared.ip = ip;
    Printer._shared.promise = new Promise(function(resolve, reject) {
        var ws;
        try {
            ws = new WebSocket('ws://' + ip + ':9090');
        } catch (e) {
            Printer._shared.promise = null;
            reject(new Error('WebSocket no soportado o IP inválida (' + ip + ')'));
            return;
        }

        var settled = false;
        var waitMs = (ip === 'localhost' || ip === '127.0.0.1') ? 2000 : 8000;
        var timeout = setTimeout(function() {
            if (settled) {
                return;
            }
            settled = true;
            Printer._shared.promise = null;
            try { ws.close(); } catch (e2) {}
            reject(new Error('Print Server no responde en ws://' + ip + ':9090'));
        }, waitMs);

        function finishOk() {
            if (settled) {
                return;
            }
            settled = true;
            clearTimeout(timeout);
            Printer._shared.ws = ws;
            Printer._shared.preferredIp = ip;
            resolve(ws);
        }

        function finishErr() {
            if (settled) {
                return;
            }
            settled = true;
            clearTimeout(timeout);
            Printer._shared.promise = null;
            try { ws.close(); } catch (e2) {}
            reject(new Error('No se pudo conectar al Print Server (' + ip + ':9090)'));
        }

        ws.onopen = finishOk;
        ws.onerror = finishErr;
        ws.onclose = function() {
            if (!settled) {
                finishErr();
                return;
            }
            Printer._shared.ws = null;
            Printer._shared.promise = null;
        };
    });

    return Printer._shared.promise;
};

Printer.connect = function(ip) {
    if (Printer._shared.ws && Printer._shared.ws.readyState === WebSocket.OPEN) {
        if (!ip || Printer._shared.ip === ip) {
            return Promise.resolve(Printer._shared.ws);
        }
    }

    var hosts = Printer.printServerHosts(ip);
    var lastError = null;

    function tryNext(index) {
        if (index >= hosts.length) {
            return Promise.reject(lastError || new Error('No se pudo conectar al Print Server'));
        }
        return Printer.connectToHost(hosts[index]).catch(function(error) {
            lastError = error;
            Printer._shared.promise = null;
            if (Printer._shared.ip === hosts[index]) {
                Printer._shared.ip = null;
            }
            return tryNext(index + 1);
        });
    }

    return tryNext(0);
};

Printer.enqueuePrint = function(printList, ip) {
    var run = Printer._sendQueue.then(function() {
        return Printer.dispatchPrint(printList, ip);
    });
    Printer._sendQueue = run.catch(function() {});
    return run;
};

Printer.dispatchPrint = function(printList, ip) {
    var payload = {
        printerName: printList.printerName,
        commands: printList.commands.slice()
    };
    var hasPrintDocument = false;
    var i;
    for (i = 0; i < payload.commands.length; i++) {
        if (payload.commands[i] && payload.commands[i].action === 'printDocument') {
            hasPrintDocument = true;
            break;
        }
    }
    if (!hasPrintDocument) {
        payload.commands.push({
            action: 'printDocument',
            text: null,
            count: 0,
            mode: false,
            imagePath: null
        });
    }

    return Printer.connect(ip).then(function(ws) {
        if (!ws || ws.readyState !== WebSocket.OPEN) {
            throw new Error('Print Server desconectado');
        }
        ws.send(JSON.stringify(payload));
        return new Promise(function(resolve) {
            setTimeout(resolve, 400);
        });
    });
};

Printer.encodeParams = function(params) {
    var parts = [];
    var key;
    for (key in params) {
        if (!params.hasOwnProperty(key) || typeof params[key] === 'undefined' || params[key] === null) {
            continue;
        }
        parts.push(encodeURIComponent(key) + '=' + encodeURIComponent(String(params[key])));
    }
    return parts.join('&');
};

Printer.pluginEndpoint = function() {
    return 'ac/imprimir_comanda_plugin.php';
};

Printer.request = function(params) {
    var endpoint = Printer.pluginEndpoint();
    var body = Printer.encodeParams(params);

    if (window.jQuery) {
        return new Promise(function(resolve, reject) {
            window.jQuery.ajax({
                url: endpoint,
                type: 'POST',
                data: body,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                dataType: 'json',
                timeout: 20000,
                success: function(data) {
                    resolve(data);
                },
                error: function(xhr, status, error) {
                    reject(new Error(error || status || 'Error de red al imprimir'));
                }
            });
        });
    }

    return new Promise(function(resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', endpoint, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xhr.timeout = 20000;
        xhr.onreadystatechange = function() {
            if (xhr.readyState !== 4) {
                return;
            }
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    resolve(JSON.parse(xhr.responseText));
                } catch (e) {
                    reject(new Error('Respuesta inválida del servidor de impresión'));
                }
            } else {
                reject(new Error('Error HTTP ' + xhr.status));
            }
        };
        xhr.ontimeout = function() {
            reject(new Error('Timeout al contactar el servidor de impresión'));
        };
        xhr.onerror = function() {
            reject(new Error('Error de red al imprimir'));
        };
        xhr.send(body);
    });
};

Printer.imprimirComandas = function(idVenta, reimprimir, tipo) {
    if (typeof reimprimir === 'undefined') {
        reimprimir = false;
    }
    if (typeof tipo === 'undefined' || !tipo) {
        tipo = 'venta';
    }

    var preparar = reimprimir
        ? Printer.request({ id_venta: idVenta, reimprimir: '1', tipo: tipo })
        : Promise.resolve();

    return preparar.then(function() {
        return Printer.request({ id_venta: idVenta, listar_impresoras: '1', tipo: tipo });
    }).then(function(response) {
        if (!response.ok) {
            throw new Error(response.error || 'No se pudieron consultar las impresoras');
        }
        if (!response.printers || !response.printers.length) {
            throw new Error('No hay impresoras configuradas para la comanda');
        }

        var cadena = Promise.resolve();
        var i;
        for (i = 0; i < response.printers.length; i++) {
            (function(printerName) {
                cadena = cadena.then(function() {
                    return Printer.request({
                        id_venta: idVenta,
                        impresora: printerName,
                        tipo: tipo
                    }).then(function(printResponse) {
                        if (!printResponse.ok) {
                            throw new Error(printResponse.error || 'No se pudo generar la comanda');
                        }
                        return Printer.enqueuePrint(printResponse.printList);
                    });
                });
            })(response.printers[i]);
        }
        return cadena;
    }).then(function() {
        return Printer.request({ id_venta: idVenta, marcar_impresa: '1', tipo: tipo });
    }).then(function(response) {
        if (!response.ok) {
            throw new Error(response.error || 'No se pudo marcar la comanda como impresa');
        }
        return response;
    });
};

Printer.imprimirTicketMesa = function(idVenta, tipo) {
    if (typeof tipo === 'undefined' || !tipo) {
        tipo = 'cobrar';
    }
    return Printer.request({
        id_venta: idVenta,
        ticket_mesa: '1',
        tipo_ticket: tipo
    }).then(function(response) {
        if (!response.ok) {
            throw new Error(response.error || 'No se pudo generar el ticket');
        }
        return Printer.enqueuePrint(response.printList);
    });
};

Printer.imprimirComandasYTicketCierre = function(idVenta, tipo) {
    if (typeof tipo === 'undefined' || !tipo) {
        tipo = 'venta';
    }
    return Printer.imprimirComandas(idVenta, false, tipo).then(function() {
        return Printer.imprimirTicketMesa(idVenta, 'cerrar');
    });
};

Printer.procesarTicketRespuesta = function(xhr, callback, idVenta, tipo) {
    var id = idVenta;
    var kind = tipo || 'cobrar';
    try {
        var ticket = xhr && xhr.getResponseHeader ? xhr.getResponseHeader('X-PV-Ticket') : '';
        if (ticket) {
            var parts = String(ticket).split('|');
            if (parts[0]) {
                id = parts[0];
            }
            if (parts[1]) {
                kind = parts[1];
            }
        }
    } catch (e) {}

    if (!id || !window.Printer || typeof Printer.imprimirTicketMesa !== 'function') {
        if (typeof callback === 'function') {
            callback();
        }
        return Promise.resolve();
    }

    return Printer.imprimirTicketMesa(id, kind).then(function() {
        if (typeof callback === 'function') {
            callback();
        }
    }).catch(function(error) {
        alert('No se pudo imprimir el ticket: ' + (error && error.message ? error.message : error));
        if (typeof callback === 'function') {
            callback();
        }
    });
};

Printer.imprimirTicketDomicilio = function(idVenta, impresora) {
    if (typeof impresora === 'undefined') {
        impresora = '';
    }
    return Printer.request({
        id_venta: idVenta,
        impresora: impresora,
        ticket_domicilio: '1'
    }).then(function(response) {
        if (!response.ok) {
            throw new Error(response.error || 'No se pudo generar el ticket de domicilio');
        }
        return Printer.enqueuePrint(response.printList);
    });
};

Printer.imprimirCorte = function(idCorte) {
    return Printer.request({ id_venta: idCorte, corte: '1' }).then(function(response) {
        if (!response.ok) {
            throw new Error(response.error || 'No se pudo generar el corte');
        }
        return Printer.enqueuePrint(response.printList);
    });
};

Printer.imprimirGasto = function(idGasto) {
    return Printer.request({ id_venta: idGasto, gasto: '1' }).then(function(response) {
        if (!response.ok) {
            throw new Error(response.error || 'No se pudo generar el ticket de gasto');
        }
        return Printer.enqueuePrint(response.printList);
    });
};

Printer.imprimirFactura = function(idFactura) {
    return Printer.request({ id_venta: idFactura, factura: '1' }).then(function(response) {
        if (!response.ok) {
            throw new Error(response.error || 'No se pudo generar el ticket de factura');
        }
        return Printer.enqueuePrint(response.printList);
    });
};

Printer.imprimirComprobanteDomicilio = function(datos) {
    return Printer.request({
        comprobante_domicilio: '1',
        nombre: datos.nombre,
        telefono: datos.telefono,
        direccion: datos.direccion
    }).then(function(response) {
        if (!response.ok) {
            throw new Error(response.error || 'No se pudo generar el comprobante de domicilio');
        }
        return Printer.enqueuePrint(response.printList);
    });
};

Printer.imprimirCodigo = function(datos) {
    return Printer.request({
        codigo: '1',
        codigo_valor: datos.codigo,
        monto: datos.monto,
        metodo: datos.metodo,
        cuenta: datos.cuenta
    }).then(function(response) {
        if (!response.ok) {
            throw new Error(response.error || 'No se pudo generar el ticket de código');
        }
        return Printer.enqueuePrint(response.printList);
    });
};

Printer.imprimirWifi = function(password) {
    return Printer.request({ wifi: '1', password: password }).then(function(response) {
        if (!response.ok) {
            throw new Error(response.error || 'No se pudo generar el ticket Wi-Fi');
        }
        return Printer.enqueuePrint(response.printList);
    }).then(function() {
        if (window.jQuery) {
            return new Promise(function(resolve, reject) {
                window.jQuery.ajax({
                    url: 'ac/imprimir_wifi.php',
                    type: 'POST',
                    data: Printer.encodeParams({ confirmar: '1', password: password }),
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    success: function(response) {
                        if (String(response).replace(/^\s+|\s+$/g, '') !== '1') {
                            reject(new Error(response || 'No se pudo registrar la contraseña Wi-Fi'));
                            return;
                        }
                        resolve();
                    },
                    error: function() {
                        reject(new Error('No se pudo registrar la contraseña Wi-Fi'));
                    }
                });
            });
        }
        return Promise.resolve();
    });
};

Printer.prototype.addCommand = function(action, text, count, mode, imagePath) {
    if (!action || typeof action !== 'string') {
        return;
    }
    this.printList.commands.push({
        action: action,
        text: typeof text === 'undefined' ? null : text,
        count: typeof count === 'undefined' ? 0 : count,
        mode: typeof mode === 'undefined' ? false : mode,
        imagePath: typeof imagePath === 'undefined' ? null : imagePath
    });
};

Printer.prototype.resetCommands = function() {
    this.printList.commands = [];
};

Printer.prototype.sendCommands = function() {
    return Printer.enqueuePrint(this.printList, this.ip);
};

Printer.prototype.printDocument = function() {
    this.addCommand('printDocument');
    var self = this;
    return this.sendCommands().then(function() {
        self.resetCommands();
    });
};

Printer.prototype.sendPrintList = function(printList) {
    if (!printList || typeof printList !== 'object') {
        return Promise.reject(new Error('Lista de impresión inválida'));
    }
    if (!printList.printerName || !printList.commands || typeof printList.commands.slice !== 'function') {
        return Promise.reject(new Error('La impresión requiere printerName y commands'));
    }
    this.printerName = printList.printerName;
    this.printList = {
        printerName: printList.printerName,
        commands: printList.commands.slice()
    };
    return this.printDocument();
};

Printer.prototype.getPrinters = function() {
    var ip = this.ip;
    return Printer.connect(ip).then(function(ws) {
        return new Promise(function(resolve, reject) {
            var timeout = setTimeout(function() {
                ws.onmessage = null;
                reject(new Error('Timeout al obtener impresoras (5s)'));
            }, 5000);
            ws.onmessage = function(event) {
                clearTimeout(timeout);
                try {
                    var message = JSON.parse(event.data);
                    resolve(message.printers || []);
                } catch (e) {
                    resolve([]);
                }
            };
            ws.send('printers');
        });
    });
};

Printer._shared = { ws: null, ip: null, promise: null, preferredIp: null };
Printer._sendQueue = Promise.resolve();

if (typeof window !== 'undefined') {
    window.Printer = Printer;

    if (window.jQuery) {
        window.jQuery(document).ajaxSuccess(function(event, xhr, settings) {
            if (!settings.url || !settings.url.match(/ac\/(cerrar_mesa|cobrar|nuevo_gasto|editar_gasto|agrega_domicilio|direccion_existe|direccion_nueva|reimprimir|genera_codigo|imprimir_wifi)\.php/)) {
                return;
            }
            var gasto = xhr.getResponseHeader('X-PV-Gasto');
            if (gasto) {
                Printer.imprimirGasto(gasto).catch(function(error) { console.error(error); });
            }
            var domicilio = xhr.getResponseHeader('X-PV-Domicilio');
            if (domicilio) {
                Printer.imprimirComprobanteDomicilio(JSON.parse(decodeURIComponent(domicilio))).catch(function(error) { console.error(error); });
            }
            var codigo = xhr.getResponseHeader('X-PV-Codigo');
            if (codigo) {
                Printer.imprimirCodigo(JSON.parse(decodeURIComponent(codigo))).catch(function(error) { console.error(error); });
            }
            var wifi = xhr.getResponseHeader('X-PV-Wifi');
            if (wifi) {
                Printer.imprimirWifi(decodeURIComponent(wifi)).catch(function(error) { console.error(error); });
            }
        });
    }
}
