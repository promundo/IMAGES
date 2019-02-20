<?php
/** 
 * The theme footer.
 * 
 * @package bootstrap-basic4
 */
?>              </div></div>
            </div><!--.site-content-->


            <footer id="site-footer" class="site-footer page-footer">
                <div class="container">
                    <div id="footer-row" class="row">
                        <div class="col-md-6 footer-left">
                            <?php dynamic_sidebar('footer-left'); ?> 
                        </div>
                        <div class="col-md-6 footer-right">
                            <?php dynamic_sidebar('footer-right'); ?> 
                        </div>
                    </div>
                </div>
            </footer><!--.page-footer-->
            <div id="overlay"></div>
        </div><!--.page-container-->


        <!--wordpress footer-->
        <?php wp_footer(); ?> 
        <!--end wordpress footer-->
    </body>
</html>
