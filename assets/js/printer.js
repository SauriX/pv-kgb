/**
 * Cliente de impresora térmica vía WebSocket
 * Soporta navegador y Node.js
 */
class Printer {
    /**
     * Constructor de la clase Printer
     * @param {string} printerName - Nombre de la impresora (puede ser null)
     * @param {string} ip - IP del servidor WebSocket (default: localhost)
     */
    constructor(printerName, ip = "localhost") {
        if (!ip || typeof ip !== 'string') {
            throw new Error('La IP del servidor debe ser una cadena válida');
        }
        
        this.printerName = printerName || "Impresora Térmica";
        this.ip = ip;
        this.isConnected = false;
        this.messageHandlers = new Map();

        if (typeof window !== 'undefined') {
            // Estamos en el navegador: usar WebSocket nativo
            this.ws = new WebSocket(`ws://${ip}:9090`);

            this.ws.addEventListener('open', () => {
                this.isConnected = true;
                console.log(`✓ Conectado a la impresora: ${this.printerName}`);
            }, { once: true });

            this.ws.addEventListener('message', (event) => {
                this._handleMessage(event.data);
            });

            this.ws.addEventListener('close', () => {
                this.isConnected = false;
                console.log(`✗ Desconectado de la impresora: ${this.printerName}`);
            });

            this.ws.addEventListener('error', (error) => {
                console.error(`⚠ Error WebSocket:`, error);
                this.isConnected = false;
            });

        } else {
            // Estamos en Node.js: usar la librería 'ws'
            const WebSocket = require('ws');
            this.ws = new WebSocket(`ws://${ip}:9090`);

            this.ws.on('open', () => {
                this.isConnected = true;
                console.log(`✓ Conectado a la impresora: ${this.printerName}`);
            });

            this.ws.on('message', (data) => {
                this._handleMessage(data.toString());
            });

            this.ws.on('close', () => {
                this.isConnected = false;
                console.log(`✗ Desconectado de la impresora: ${this.printerName}`);
            });

            this.ws.on('error', (error) => {
                console.error(`⚠ Error WebSocket:`, error.message);
                this.isConnected = false;
            });
        }

        // Inicializar la lista de comandos
        this.printList = {
            printerName: this.printerName,
            commands: []
        };
    }

    /**
     * Maneja mensajes recibidos del servidor
     * @private
     */
    _handleMessage(data) {
        try {
            const message = JSON.parse(data);
            console.log('📨 Mensaje recibido:', message);
            
            // Buscar callbacks registrados para este mensaje
            if (this.messageHandlers.has('printers') && message.printers) {
                const handler = this.messageHandlers.get('printers');
                handler(message.printers);
                this.messageHandlers.delete('printers');
            }
        } catch (error) {
            console.error('Error al procesar mensaje:', error);
        }
    }

    /**
     * Agrega un comando a la lista de impresión
     * @param {string} action - Tipo de acción
     * @param {string} text - Texto (opcional)
     * @param {number} count - Contador (opcional)
     * @param {boolean} mode - Modo (opcional)
     * @param {string} imagePath - Ruta de imagen (opcional)
     */
    addCommand(action, text = null, count = 0, mode = false, imagePath = null) {
        if (!action || typeof action !== 'string') {
            console.warn('⚠ Acción inválida:', action);
            return;
        }

        const command = {
            action: action,
            text: text,
            count: count,
            mode: mode,
            imagePath: imagePath
        };

        this.printList.commands.push(command);
        console.debug(`📝 Comando agregado: ${action}`);
    }

    /**
     * Limpia la lista de comandos
     */
    resetCommands() {
        this.printList.commands = [];
        console.log('🔄 Lista de comandos reiniciada');
    }

    /**
     * Retorna el número de comandos en la lista
     */
    getCommandCount() {
        return this.printList.commands.length;
    }

    /**
     * Verifica si la conexión WebSocket está activa
     */
    checkConnection() {
        return this.isConnected && this.ws && this.ws.readyState === (typeof window !== 'undefined' ? WebSocket.OPEN : 1);
    }

    /**
     * Envía los comandos acumulados al servidor
     * @returns {Promise<void>}
     */
    sendCommands() {
        return new Promise((resolve, reject) => {
            const timeout = setTimeout(() => {
                reject(new Error('Timeout al enviar comandos (10s)'));
            }, 10000);

            const sendData = () => {
                try {
                    const jsonData = JSON.stringify(this.printList);
                    this.ws.send(jsonData);
                    console.log(`✓ ${this.printList.commands.length} comandos enviados`);
                    clearTimeout(timeout);
                    resolve();
                } catch (error) {
                    clearTimeout(timeout);
                    reject(new Error(`Error al enviar: ${error.message}`));
                }
            };

            const openHandler = () => {
                if (typeof window !== 'undefined') {
                    this.ws.removeEventListener('open', openHandler);
                }
                sendData();
            };

            if (this.checkConnection()) {
                sendData();
            } else {
                console.log('⏳ Esperando conexión WebSocket...');
                
                if (typeof window !== 'undefined') {
                    this.ws.addEventListener('open', openHandler, { once: true });
                } else {
                    this.ws.once('open', openHandler);
                }
            }
        });
    }
    
    /**
     * Obtiene la lista de impresoras disponibles
     * @returns {Promise<Array>} Array de nombres de impresoras
     */
    getPrinters() {
        return new Promise((resolve, reject) => {
            const timeout = setTimeout(() => {
                this.messageHandlers.delete('printers');
                reject(new Error('Timeout al obtener impresoras (5s)'));
            }, 5000);

            // Registrar handler para la respuesta
            this.messageHandlers.set('printers', (printers) => {
                clearTimeout(timeout);
                resolve(printers || []);
            });

            const sendRequest = () => {
                try {
                    this.ws.send('printers');
                    console.log('🔍 Solicitando lista de impresoras...');
                } catch (error) {
                    clearTimeout(timeout);
                    this.messageHandlers.delete('printers');
                    reject(new Error(`Error al solicitar impresoras: ${error.message}`));
                }
            };

            if (this.checkConnection()) {
                sendRequest();
            } else {
                console.log('⏳ Esperando conexión para solicitar impresoras...');
                
                if (typeof window !== 'undefined') {
                    this.ws.addEventListener('open', sendRequest, { once: true });
                } else {
                    this.ws.once('open', sendRequest);
                }
            }
        });
    }
    

    // Funciones correspondientes a cada acción
    printText(text) {
        this.addCommand('text', text);
    }

    cutPartial() {
        this.addCommand('partial');
    }

    cutFull() {
        this.addCommand('full');
    }

    printDocument() {
        this.addCommand('printDocument');
        
        // Enviar e inmediatamente limpiar la lista
        return this.sendCommands().then(() => {
            this.resetCommands();
        }).catch((error) => {
            console.error('✗ Error al imprimir:', error.message);
            throw error;
        });
    }

    testPrinter() {
        this.addCommand('testPrinter');
    }

    sendPrintList(printList) {
        if (!printList || typeof printList !== 'object') {
            return Promise.reject(new Error('Lista de impresión inválida'));
        }
        if (!printList.printerName || !Array.isArray(printList.commands)) {
            return Promise.reject(new Error('La impresión requiere printerName y commands'));
        }

        this.printerName = printList.printerName;
        this.printList = {
            printerName: printList.printerName,
            commands: printList.commands.slice()
        };

        return this.printDocument();
    }

    static pluginEndpoint() {
        if (typeof window === 'undefined' || !window.document) {
            return 'ac/imprimir_comanda_plugin.php';
        }
        const script = window.document.querySelector('script[src*="printer.js"]');
        if (!script || !script.src) {
            return 'ac/imprimir_comanda_plugin.php';
        }
        return new URL('../../ac/imprimir_comanda_plugin.php', script.src).toString();
    }

    static imprimirComandas(idVenta, reimprimir = false, tipo = 'venta') {
        const endpoint = Printer.pluginEndpoint();
        const request = (params) => fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams(params)
        }).then((response) => response.json());

        const preparar = reimprimir
            ? request({ id_venta: idVenta, reimprimir: '1', tipo: tipo })
            : Promise.resolve();

        return preparar.then(() => request({ id_venta: idVenta, listar_impresoras: '1', tipo: tipo }))
            .then((response) => {
                if (!response.ok) {
                    throw new Error(response.error || 'No se pudieron consultar las impresoras');
                }
                if (!Array.isArray(response.printers) || response.printers.length === 0) {
                    throw new Error('No hay impresoras configuradas para la comanda');
                }
                return Promise.all(response.printers.map((printerName) => request({
                    id_venta: idVenta,
                    impresora: printerName,
                    tipo: tipo
                }).then((printResponse) => {
                    if (!printResponse.ok) {
                        throw new Error(printResponse.error || 'No se pudo generar la comanda');
                    }
                    return new Printer(printResponse.printList.printerName).sendPrintList(printResponse.printList);
                })));
            })
            .then(() => request({ id_venta: idVenta, marcar_impresa: '1', tipo: tipo }))
            .then((response) => {
                if (!response.ok) {
                    throw new Error(response.error || 'No se pudo marcar la comanda como impresa');
                }
                return response;
            });
    }

    static imprimirTicketMesa(idVenta, tipo = 'cobrar') {
        return fetch(Printer.pluginEndpoint(), {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ id_venta: idVenta, ticket_mesa: '1', tipo_ticket: tipo })
        })
            .then((response) => response.json())
            .then((response) => {
                if (!response.ok) {
                    throw new Error(response.error || 'No se pudo generar el ticket');
                }
                return new Printer(response.printList.printerName).sendPrintList(response.printList);
            });
    }

    static imprimirTicketDomicilio(idVenta, impresora = '') {
        return fetch(Printer.pluginEndpoint(), {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ id_venta: idVenta, impresora: impresora, ticket_domicilio: '1' })
        })
            .then((response) => response.json())
            .then((response) => {
                if (!response.ok) {
                    throw new Error(response.error || 'No se pudo generar el ticket de domicilio');
                }
                return new Printer(response.printList.printerName).sendPrintList(response.printList);
            });
    }

    static imprimirCorte(idCorte) {
        return fetch(Printer.pluginEndpoint(), {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ id_venta: idCorte, corte: '1' })
        })
            .then((response) => response.json())
            .then((response) => {
                if (!response.ok) {
                    throw new Error(response.error || 'No se pudo generar el corte');
                }
                return new Printer(response.printList.printerName).sendPrintList(response.printList);
            });
    }

    static imprimirGasto(idGasto) {
        return fetch(Printer.pluginEndpoint(), {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ id_venta: idGasto, gasto: '1' })
        })
            .then((response) => response.json())
            .then((response) => {
                if (!response.ok) {
                    throw new Error(response.error || 'No se pudo generar el ticket de gasto');
                }
                return new Printer(response.printList.printerName).sendPrintList(response.printList);
            });
    }

    static imprimirFactura(idFactura) {
        return fetch(Printer.pluginEndpoint(), {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ id_venta: idFactura, factura: '1' })
        })
            .then((response) => response.json())
            .then((response) => {
                if (!response.ok) {
                    throw new Error(response.error || 'No se pudo generar el ticket de factura');
                }
                return new Printer(response.printList.printerName).sendPrintList(response.printList);
            });
    }

    static imprimirComprobanteDomicilio(datos) {
        return fetch(Printer.pluginEndpoint(), {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                comprobante_domicilio: '1',
                nombre: datos.nombre,
                telefono: datos.telefono,
                direccion: datos.direccion
            })
        })
            .then((response) => response.json())
            .then((response) => {
                if (!response.ok) {
                    throw new Error(response.error || 'No se pudo generar el comprobante de domicilio');
                }
                return new Printer(response.printList.printerName).sendPrintList(response.printList);
            });
    }

    static imprimirCodigo(datos) {
        return fetch(Printer.pluginEndpoint(), {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                codigo: '1',
                codigo_valor: datos.codigo,
                monto: datos.monto,
                metodo: datos.metodo,
                cuenta: datos.cuenta
            })
        })
            .then((response) => response.json())
            .then((response) => {
                if (!response.ok) {
                    throw new Error(response.error || 'No se pudo generar el ticket de código');
                }
                return new Printer(response.printList.printerName).sendPrintList(response.printList);
            });
    }

    static imprimirWifi(password) {
        return fetch(Printer.pluginEndpoint(), {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ wifi: '1', password: password })
        })
            .then((response) => response.json())
            .then((response) => {
                if (!response.ok) {
                    throw new Error(response.error || 'No se pudo generar el ticket Wi-Fi');
                }
                return new Printer(response.printList.printerName).sendPrintList(response.printList);
            })
            .then(() => fetch('ac/imprimir_wifi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ confirmar: '1', password: password })
            }))
            .then((response) => response.text())
            .then((response) => {
                if (response.trim() !== '1') {
                    throw new Error(response || 'No se pudo registrar la contraseña Wi-Fi');
                }
            });
    }

    code123(text) {
        this.addCommand('code123', text);
    }

    code39(text) {
        this.addCommand('code39', text);
    }

    ean13(text) {
        this.addCommand('ean13', text);
    }

    openDrawer() {
        this.addCommand('openDrawer');
    }

    separator(text) {
        this.addCommand('separator', text || '');
    }

    bold(text) {
        this.addCommand('bold', text);
    }

    underLine(text) {
        this.addCommand('underLine', text);
    }

    expanded(mode) {
        this.addCommand('expanded', null, 0, mode);
    }

    condensed(mode) {
        this.addCommand('condensed', null, 0, mode);
    }

    doubleWidth2() {
        this.addCommand('doubleWidth2');
    }

    doubleWidth3() {
        this.addCommand('doubleWidth3');
    }

    normalWidth() {
        this.addCommand('normalWidth');
    }

    alignRight() {
        this.addCommand('right');
    }

    alignCenter() {
        this.addCommand('center');
    }

    alignLeft() {
        this.addCommand('left');
    }

    fontA(text) {
        this.addCommand('fontA', text);
    }

    fontB(text) {
        this.addCommand('fontB', text);
    }

    fontC(text) {
        this.addCommand('fontC', text);
    }

    fontD(text) {
        this.addCommand('fontD', text);
    }

    fontE(text) {
        this.addCommand('fontE', text);
    }

    fontEspecialA(text) {
        this.addCommand('fontEspecialA', text);
    }

    fontEspecialB(text) {
        this.addCommand('fontEspecialB', text);
    }

    initializePrint() {
        this.addCommand('initializePrint');
    }

    lineHeight(count) {
        this.addCommand('lineHeight', null, count);
    }

    newLines(count) {
        this.addCommand('newLines', null, count);
    }

    newLine() {
        this.addCommand('newLine');
    }
}


if (typeof module !== 'undefined' && module.exports) {
    module.exports = Printer;
}

if (typeof window !== 'undefined') {
    window.Printer = Printer;

    if (window.jQuery) {
        window.jQuery(document).ajaxSuccess((event, xhr, settings, data) => {
            if (!settings.url.match(/ac\/(cerrar_mesa|cobrar|nuevo_gasto|editar_gasto|agrega_domicilio|direccion_existe|direccion_nueva|reimprimir|genera_codigo|imprimir_wifi)\.php/)) {
                return;
            }
            const ticket = xhr.getResponseHeader('X-PV-Ticket');
            if (ticket) {
                const response = ticket.split('|');
                Printer.imprimirTicketMesa(response[0], response[1]).catch((error) => console.error(error));
            }
            const gasto = xhr.getResponseHeader('X-PV-Gasto');
            if (gasto) {
                Printer.imprimirGasto(gasto).catch((error) => console.error(error));
            }
            const domicilio = xhr.getResponseHeader('X-PV-Domicilio');
            if (domicilio) {
                Printer.imprimirComprobanteDomicilio(JSON.parse(decodeURIComponent(domicilio))).catch((error) => console.error(error));
            }
            const codigo = xhr.getResponseHeader('X-PV-Codigo');
            if (codigo) {
                Printer.imprimirCodigo(JSON.parse(decodeURIComponent(codigo))).catch((error) => console.error(error));
            }
            const wifi = xhr.getResponseHeader('X-PV-Wifi');
            if (wifi) {
                Printer.imprimirWifi(decodeURIComponent(wifi)).catch((error) => console.error(error));
            }
        });
    }
}
