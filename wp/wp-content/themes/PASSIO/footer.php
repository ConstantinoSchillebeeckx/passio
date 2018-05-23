			<!-- footer -->
			<footer class="footer" role="contentinfo">

				<!-- copyright -->
				<p class="copyright">
					Copyright &copy; <?php echo date('Y'); ?> PASSIO Surgical Education. All Rights Reserved.
				</p>
				<!-- /copyright -->

			</footer>
			<!-- /footer -->

		</div>
		<!-- /container -->

		<?php wp_footer(); ?>




		<script>
			$(function () {
			  $('[data-toggle="tooltip"]').tooltip()
			})
		</script>


		<script>

       			 // conditionizr.com
     			 // configure environment tests
        			conditionizr.config({
            				assets: '<?php echo get_template_directory_uri(); ?>',
            				tests: {}
        			});
 	       </script>

		<!-- analytics -->
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-75420240-1', 'auto');
		  ga('send', 'pageview');

		</script>

	</body>
</html>
