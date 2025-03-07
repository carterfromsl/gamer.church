<?php ?>

			</div>

			<div class="t-debug" id="c-debug"><span class="t-close" onclick="closeCol('c-debug')">[X]</span>
				<div id="debugFeed"></div>
			</div>
			<footer id="t-footer">
				<p><em>// Copyright <a href="https://gamer.church/" target="_blank">GAMER.CHURCH</a> <span id="getYear">2025</span></em></p>
				
				<script>
					function getYear() {
						document.getElementById("getYear").innerHTML = new Date().getFullYear();
					}
					getYear();
				</script>
			</footer>
		</div>
	<?php wp_footer(); ?>

	</body>
</html>
