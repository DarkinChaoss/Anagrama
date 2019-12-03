				<script>
					$(".data").datepicker({
						format: "dd/mm/yyyy"
					});
					$(".hora").mask("99:99:99");


					//Cerquilha # free
					$(document).click(function(){

						setTimeout(function(){
					    	var url_atual = window.location.pathname
					    	var search = window.location.search
					    	window.history.replaceState('Object', '', url_atual + search);
						}, 50);
						
					});
					

				</script>
				
			</div> <!-- /hero-unit -->
			
			<hr>
			
			<footer>
				<img src="img/<?php /*echo (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa");*/ ?>logoEmpresa.png" class="pull-right" style="width: 150px;">
				<div class="row-fluid">
					<div class="span3" style="width: 200px;">
						<p>
							Medtracker. © 2019<br>
							<a href="http://www.medtracker.com.br" target="_blank">www.medtracker.com.br</a>
						</p>
					</div>
					<?php
						if($_SESSION['usu_nivel'] >= 4){
							// echo '	<div class="span5" style="border-left: 1px solid #bbbbbb; padding: 10px 0 0 30px;">
							// 			<p>
							// 				<a href="https://docs.google.com/spreadsheet/pub?key=0Apl9lPgsEbWRdHJyUGNXcnFZQ0RGblZ1MV91Mm5TX1E&single=true&gid=1&output=html" target="_blank">Atualizações</a>
							// 			</p>
							// 		</div>';
						}
					?>
				</div>
			</footer>
		</div> <!-- /container -->
	</body>
</html>

<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2015
*/
?>