<?php

set_time_limit(0);
extract($_POST);

$rfc = rawurlencode($rfc);
$razon_social = rawurlencode($razon_social);
$email = rawurlencode($email);
$cantidad = rawurlencode($cantidad);
$unidad = rawurlencode($unidad);
$clave = rawurlencode($clave);
$descripcion = rawurlencode($descripcion);
$importe = rawurlencode($importe);
$metodo_pago = rawurlencode($metodo_pago);
//$digitos = rawurlencode($digitos);
$iva = rawurlencode($iva);
$observacion = rawurlencode($observacion);
$total = rawurlencode($total);


//$datos = "?rfc=$rfc&razon_social=$razon_social&email=$email&cantidad=$cantidad&unidad=$unidad&clave=$clave&descripcion=$descripcion&importe=$importe&metodo_pago=$metodo_pago&digitos=$digitos&iva=$iva&observacion=$observacion&total=$total";
$datos = "?rfc=$rfc&razon_social=$razon_social&email=$email&cantidad=$cantidad&unidad=$unidad&clave=$clave&descripcion=$descripcion&importe=$importe&metodo_pago=$metodo_pago&iva=$iva&observacion=$observacion&total=$total";

include('../includes/db.php');

if($facturar_saldo==1):
    $facturado = 0;
    $monto_pendiente = $monto_real-$total;
else:
    $facturado = 1;
endif;

mysql_query("UPDATE ventas SET facturado = $facturado, pendiente_facturar = '$facturar_saldo', pendiente_monto = '$monto_pendiente' WHERE id_venta = $id_venta");

//echo $datos; exit;

$url = "http://tacoloco.mx/facturacion/web_services_facturacion_kgb_magisterial.php";
file_put_contents('test.txt', $url.$datos);
$http = new HttpConnection();
$http->init();
//echo file_get_contents($url.$datos);
echo $http->get($url.$datos);
$http->close();

class HttpConnection
{
    private $curl;
    private $cookie;
    private $cookie_path="/cookies";
    private $id;

    public function __construct() {
        $this->id = time();
    }
    /**
     * Inicializa el objeto curl con las opciones por defecto.
     * Si es null se crea
     * @param string $cookie a usar para la conexion
     */
    public function init($cookie=null) {
        if($cookie)
            $this->cookie = $cookie;
        else
            $this->cookie = $this->cookie_path . $this->id;

        $this->curl=curl_init();
        curl_setopt($this->curl, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1");
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE,$this->cookie);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array("Accept-Language: es-es,en"));
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookie);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, TRUE);
}
    /**
     * Establece en que ruta se guardan las cookies.
     * Importante: El usuario de apache debe tener acceso de lectura y escritura
     * @param string $path
     */
    public function setCookiePath($path){
        $this->cookie_path = $path;
    }
    /**
     * Envía una peticion GET a la URL especificada
     * @param string $url
     * @param bool $follow
     * @return string Respuesta generada por el servidor
     */
    public function get($url,$follow=false) {
        $this->init();
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST,false);
        curl_setopt($this->curl, CURLOPT_HEADER, $follow);
        curl_setopt($this->curl, CURLOPT_REFERER, '');
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, $follow);
        $result=curl_exec ($this->curl);
        if($result === false){
            echo curl_error($this->curl);
        }
        $this->_close();
        return $result;
    }
    /**
     * Envía una petición POST a la URL especificada
     * @param string $url
     * @param array $post_elements
     * @param bool $follow
     * @param bool $header
     * @return string Respuesta generada por el servidor
     */
    public function post($url,$post_elements,$follow=false,$header=false) {
        $this->init();
        $elements=array();
        foreach ($post_elements as $name=>$value) {
            $elements[] = "{$name}=".urlencode($value);
        }
        $elements = join("&",$elements);
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST,true);
        curl_setopt($this->curl, CURLOPT_REFERER, '');
        curl_setopt($this->curl, CURLOPT_HEADER, $header OR $follow);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $elements);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, $follow);
        $result=curl_exec ($this->curl);
        $this->_close();
        return $result;
    }
    /**
     * Descarga un fichero binario en el buffer
     * @param string $url
     * @return string
     */
    public function getBinary($url){
        $this->init();
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_BINARYTRANSFER,1);
        $result = curl_exec ($this->curl);
        $this->_close();
        return $result;
    }
    /**
     * Cierra la conexión
     */
    private function _close() {
        curl_close($this->curl);
    }
    public function close(){
        if(file_exists($this->cookie))
            unlink($this->cookie);
    }
}
