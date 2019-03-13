<?php
/**
 * Define Solr host IP, port, scheme and path
 * Update these as necessary if your configuration differs
*/
putenv( 'PANTHEON_INDEX_HOST=ec2-18-197-33-91.eu-central-1.compute.amazonaws.com' );
putenv( 'PANTHEON_INDEX_PORT=8080' );
add_filter( 'solr_scheme', function(){ return 'http'; });
define( 'SOLR_PATH', '/solr' );