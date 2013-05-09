<?php 
global $opts; 
?>
	
	<div class="clearfix"></div>
	
	<footer class="bottom">	
		<!-- First row -->
		<ul class="contact">
			<?php
				if( !empty($opts['sc_address']) ){
					?>
						<li class="addr"><?php echo $opts['sc_address']; ?></li>
					<?php
				}
			?>
			<?php
				if( !empty($opts['sc_phone']) ){
					?>
						<li class="phone"><?php echo $opts['sc_phone']; ?></li>
					<?php
				}
			?>
			<?php
				if( !empty($opts['sc_email']) ){
					?>
						<li class="email"><a href="<?php echo $opts['sc_email']; ?>"><?php echo $opts['sc_email']; ?></a></li>
					<?php
				}
			?>
		</ul>
		
		<div class="clearfix"></div>
	</footer>

</div> <!-- container -->


<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
    var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s)}(document,'script'));
</script>

</body>
</html>