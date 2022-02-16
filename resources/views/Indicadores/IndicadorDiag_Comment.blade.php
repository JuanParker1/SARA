<div layout layout-align="center center" class="margin-bottom-5">
	
	<div class="s25 bg-lightgrey border-rounded border margin-right-5" 
		style="background-image: url({{ C.autor.avatar }}); background-size: cover; background-position: top center;"></div>
	<b flex class="">{{ C.autor.Nombres }}</b>
	<div class="comment_pill lh20 h20">{{ C.Op1 }}</div>
</div>

<p class="no-margin text-16px">
	{{ C.Comentario }}
</p>

<md-button class="md-raised margin-5-0 md-warn bg-warmblue" 
	ng-if="C.Grupo == 'Accion' && C.Op4" ng-click="seeExternal(C.Op4)">
	<md-icon md-font-icon="fa-external-link-alt fa-fw margin-right"></md-icon>Ver Acción
</md-button>

<div layout class="margin-top-5 comment-details">
	<span flex></span>
	<span class="text-clear text-12px Pointer"><md-tooltip md-direction=left>{{ C.created_at }}</md-tooltip>{{ C.hace }}</span>
</div>