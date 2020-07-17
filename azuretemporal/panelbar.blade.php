<ul id="panelbar">
	<li>
		{{ link_to("/", "Inicio")}}
	</li>
	<li>
		{{ HTML::linkRoute('redimir-boletos', 'Redimir boletos') }}
	</li>
	<li>
		{{ link_to("/users/logout", "Cerrar sesión")}}
	</li>
</ul>
