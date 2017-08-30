<?php

//protocol://subdomain.domain.tld:port/path/to/somewhere?query=parameters&q=p#fragment

class route
{
    public $url;
    public $protocol;
    public $subdomain;
    public $domain;
    public $tld;
    public $port;
    public $base;
    public $path;
    public $query;
    public $fragment; #this value is never sent to the server
    public $pathitem;

    public function __construct ()
    {
        $this -> url = $_SERVER [ 'HTTP_HOST' ] . $_SERVER [ 'REQUEST_URI' ];
        $this -> url = trim ( $this -> url, '/' );
        $this -> path = parse_url ( 'http://' . $this -> url, PHP_URL_PATH ); // -> /path/to/somewhere
        $this -> path = trim ( $this -> path, '/' ); // -> path/to/somewhere
        $this -> domain = explode ( ':', $_SERVER [ 'HTTP_HOST' ] );
        $this -> domain = $this -> domain [ 0 ];
        $this -> subdomain = $this -> domain;
        //TODO: create better tld detection .live.uk
        preg_match ( '/(?<=\.)\w+$/', $this -> domain, $output_array );
        $this -> tld = $output_array [ 0 ];
        //TODO: create better domain detection
        $this -> domain = explode ( '.', $this -> domain );
        $this -> domain = array_reverse ( $this -> domain );
        $this -> domain = $this -> domain [ 1 ] . '.' . $this -> domain [ 0 ];
        $this -> subdomain = str_replace ( $this -> domain, '', $this -> subdomain );
        $this -> subdomain = trim ( $this -> subdomain, '.' );
        /*
        $this -> query = explode ( "?", $_SERVER [ 'REQUEST_URI' ] );
        if ( isset ( $this -> query [ 1 ] ) )
            $this -> query = $this -> query [ 1 ];
        else
            $this -> query = false;
        */
        $this -> query = parse_url ( 'http://' . $this -> url, PHP_URL_QUERY );
        $this -> fragment = parse_url ( 'http://' . $this -> url, PHP_URL_FRAGMENT );
    }

    public function get_segment ( $index )
    {
        $_path = explode ( "/", $this -> path );
        if ( !is_array ( $_path ) ) return false;
        if ( $index > count ( $_path ) - 1 ) return false;
        return $_path [ $index ];
    }

    public function __destruct ()
    {
    }

}
