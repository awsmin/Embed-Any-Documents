<?php 
	$mimetypes = array(
	// Text formats
	'txt|asc|c|cc|h'               => 'text/plain',
	'rtx'                          => 'text/richtext',
	'css'                          => 'text/css',
	// Misc application formats
	'js'                           => 'application/javascript',
	'pdf'                          => 'application/pdf',
	'ai'						   => 'application/postscript',
	'tif'						   => 'image/tiff',
	'tiff'						   => 'image/tiff',
	// MS Office formats
	'doc'                          => 'application/msword',
	'pot|pps|ppt'                  => 'application/vnd.ms-powerpoint',
	'xla|xls|xlt|xlw'              => 'application/vnd.ms-excel',
	'docx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	'dotx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
	'dotm'                         => 'application/vnd.ms-word.template.macroEnabled.12',
	'xlsx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
	'xlsm'                         => 'application/vnd.ms-excel.sheet.macroEnabled.12',
	'pptx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
	// iWork formats
	'pages'                        => 'application/vnd.apple.pages',
	//Additional Mime Types
	'svg'						   =>  'image/svg+xml',
	);
	$extensions['all']		=	array('.css','.js','.pdf','.ai','.tif','.tiff','.doc','.txt','.asc','.c','.cc','.h','.pot','.pps','.ppt','.xla','.xls','.xlt','.xlw','.docx','.dotx','.dotm','.xlsx','.xlsm','.pptx','.pages','.svg');
	$extensions['ms'] 		=	array('.doc','.pot','.pps','.ppt','.xla','.xls','.xlt','.xlw','.docx','.dotx','.dotm','.xlsx','.xlsm','.pptx');
?>	