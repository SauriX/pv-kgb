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


module.exports = Printer;
