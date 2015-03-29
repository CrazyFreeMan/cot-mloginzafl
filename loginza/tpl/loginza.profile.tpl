<!-- BEGIN:MAIN -->
<div style="color:green;">{LZ_INFO}</div>                  
		<!-- IF {LZ_IDENTI} -->
		<div>{PHP.L.lz_profile_save}: <a href="{LZ_IDENTI}" target="blank">{LZ_IDENTI}</a></div>
		<!-- ELSE -->
		<div class="desc">{PHP.L.lz_profile_enter}</div>
		<!-- ENDIF -->
		{PHP.L.lz_ak}:
		<div>
				<a href="https://loginza.ru/api/widget?token_url={TOKEN_URL_SHORT}" class="loginza">
					<img src="{PHP.cfg.mainurl}/plugins/loginza/img/mloginfl50_50.png" height="50" width="50" alt="{PHP.L.lz_ak}" title="{PHP.L.lz_ak}"  />		
				</a>
		</div>  
<!-- END:MAIN -->