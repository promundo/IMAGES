<?php
/**
 * Define Solr host IP, port, scheme and path
 * Update these as necessary if your configuration differs
*/
putenv( 'PANTHEON_INDEX_HOST=192.168.50.4' );
putenv( 'PANTHEON_INDEX_PORT=8983' );
add_filter( 'solr_scheme', function(){ return 'http'; });
define( 'SOLR_PATH', '/solr/wordpress/' );