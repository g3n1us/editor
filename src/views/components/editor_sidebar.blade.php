<style>
.alert:empty{
	display: none;
}	
	
.cke_ltr .cke_button__templates_icon{
	width: 100px !important;
	color: #0000b6;
	background: none !important;
	cursor: pointer;
/* 	background: url(icons.png?t=320013d) no-repeat 0 -256px !important;	 */
}

.cke_ltr .cke_button__templates_icon::before{
	content: "templates";
	text-align: center;
	display: block;
}

.editor_sidebar{
	background-color: #e8e8e8; 
	box-shadow: inset 0px 0px 6px black; 
	border-radius: .5rem; 
	max-height: 100vh; 
	width: 100%;
	overflow-x: auto;
	overflow-y: visible;
	z-index: 1;
}


#templates_dragger .drag_template *{
	text-align: initial;
	cursor: -webkit-grab;
	cursor: grab;
}
	


#templates_dragger .drag_template{
	transition: all .1s ease-in;	
	transform: scale(.8) rotate(0deg);
	transform-origin: center center;
	text-align: center;
	position: relative;
}

#templates_dragger .drag_template:hover{
	transform: scale(1) rotate(5deg);
	z-index: 999;
	cursor: -webkit-grab;
	cursor: grab;
	
}	

#templates_dragger .drag_template:active{
	transform: scale(1.03) rotate(7deg);
	
	cursor: -webkit-grabbing;
	cursor: grabbing;
	
}	

#templates_dragger .drag_template::after{
	content: " ";
	position: absolute;
	top: 0; bottom: 0; left: 0; right: 0;
	width: 100%;
	height: 100%;
}

.grabbing, .grabbing *{
	cursor: -webkit-grabbing !important;
}	

input::-webkit-input-placeholder{
	opacity: .1;
	color: red;
}
	
</style>

			<div class="card card-body card-block sticky-top editor_sidebar">
				<a class="btn btn-primary btn-sm mb-3" href="#" onclick="window.open('/editor_dashboard/filemanager?v=123', 'Filemanager', 'scrollbars=yes, width=1200, height=800, top=0, left=200'); return false;">Open Filemanager</a>
				<h5 style="font-family: monospace">Drag Widgets into Editor</h5>
				<div id="templates_dragger">
					
				</div>
			</div>
