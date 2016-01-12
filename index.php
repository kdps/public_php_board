<?php

if(!isset($playlist))
{
	$playlist = "{'title':'larkspur','url':''}";
}

//define
define('__SP__', TRUE);

ini_set('gd.jpeg_ignore_warning', true);

//session
session_start();

include(dirname(__FILE__).'/conf/include.php');

if(!isset($message_skin)){
	$message_skin = 'default';
}

//default
if(!isset($layout_skin)){
	$layout_skin = 'bootstrap';
}

if(($captcha_auth === TRUE && $captcha_use==TRUE) || $captcha_use==FALSE)
{
	if(isset($post_arr['target']) && isset($post_arr['mode']))
	{
		if(isset($post_arr['mode']))
		{
			if($post_arr['mode']=='insert')
			{
				if($post_arr['target'] === 'board')
				{
					$post_date = date("YmdHis");
					$nickname = '익명';
					
					$lastId = $oModel->getBoardSequence($post_arr['board']);
					
					if(!isset($post_arr['category']))
					{
						$post_arr['category'] = 0;
					}
					
					$post_arr['title'] = iconv("EUC-KR", "UTF-8",$post_arr['title']);
					$post_arr['content'] = iconv("EUC-KR", "UTF-8",$post_arr['content']);
					
					//filter
					$post_arr['title'] = $purifier->purify($post_arr['title']);
					$post_arr['content'] = $purifier->purify($post_arr['content']);
					
					
					$oController->insertDocument($post_arr['title'], $post_arr['content'], $post_date, $nickname, $post_arr['board'], $post_arr['category'], $lastId);
					
					$get_lastid = $oController->pdo->lastInsertId('srl');
					
					header("Location: ".$oFunc->getUrl('bd',$post_arr['board'],'act','view','srl',$get_lastid));
					
				}
				elseif($post_arr['target'] === 'comment')
				{
					if(isset($post_arr['parent']))
					{
						//filter
						$post_arr['content'] = $purifier->purify($post_arr['content']);
						$post_arr['nickname'] = $purifier->purify($post_arr['nickname']);
						$oController->insertReplyComment($post_arr['board'], $post_arr['content'], $post_arr['nickname'], $post_arr['serial'], $post_arr['parent']);
						
						header("Location: ".$oFunc->getUrl('bd',$post_arr['board'],'act','view','srl',$post_arr['serial']));
					}
					else
					{
						//filter
						$post_arr['content'] = $purifier->purify($post_arr['content']);
						$post_arr['nickname'] = $purifier->purify($post_arr['nickname']);
						
						$oController->insertComment($post_arr['board'], $post_arr['content'], $post_arr['nickname'], $post_arr['serial']);
						
						header("Location: ".$oFunc->getUrl('bd',$post_arr['board'],'act','view','srl',$post_arr['serial']));
					}
				}
			}
		}
	}
}


if($ajax_mode===TRUE)
{
	if(isset($post_arr['act']))
	{
		//Voted, Blamed
		if(isset($post_arr['srl']))
		{
			if($post_arr['act'] === "vote")
			{
				$voted_count = $oModel->getVotedCount($post_arr['srl']);
				$vote_count = $voted_count['voted']+1;
				if($oController->UpdateVotedCount($vote_count, $post_arr['srl']))
				{
					echo $vote_count;
				}
				else
				{
					echo $voted_count['voted'];
				}
			}
			elseif($post_arr['act'] === "blamed")
			{
				$voted_count = $oModel->getBlamedCount($post_arr['srl']);
				$vote_count = $voted_count['blamed']+1;
				if($oController->UpdateBlamedCount($vote_count, $post_arr['srl']))
				{
					echo $vote_count;
				}
				else
				{
					echo $voted_count['blamed'];
				}
			}
		}
		
		if($post_arr['act'] === "comment_insert")
		{
			$oController->insertComment($post_arr['board'], $post_arr['content'], $post_arr['nickname'], $post_arr['serial']);
			$comment_list = $oModel->getCommentList($post_arr['board'], $post_arr['serial']);
			
			$board_skin = $oModel->getSkin($post_arr['board']);
			require_once ($absolute_directory.'/module/board/'.$board_skin.'/comment.php');
			
		}
	}
	
	
	if(isset($post_arr['mode']))
	{
		if($post_arr['mode'] === 'ajax')
		{
			//get lastid
			$ajax_id = $oModel->getTableSequence();
			
			//reader thumbnail path
			$path_ajax = $absolute_directory.'/attach/file/'.$ajax_id.'/';
			
			//upload process
			echo $oController->UploadAjaxFileJSON($sub_directory, 'upload_file', $path_ajax, $ajax_id);
		}
	}
}


//upload file
if($ajax_mode === FALSE)
{
	if(isset($_FILES['upload_file']))
	{
		if(($captcha_auth==TRUE && $captcha_use === TRUE) || $captcha_use === FALSE)
		{
			//upload file count
			$file_count = count($_FILES['upload_file']['name']);
			if(count($_FILES['upload_file']['name'])>0)
			{
				
				
				//file path
				$oFunc->makeDir($absolute_directory.'/attach/file/'.$get_lastid, 0755);
				
				//thumbnail path
				$oFunc->makeDir($absolute_directory.'/attach/thumb/'.$get_lastid, 0755);
				
				//reader thumbnail path
				$oFunc->makeDir($absolute_directory.'/attach/reader/'.$get_lastid, 0755);
				
				//view thumbnail path
				$oFunc->makeDir($absolute_directory.'/attach/thumb_index/'.$get_lastid, 0755);
				
				$file_count = count($_FILES['upload_file']['name'])-1;
				$j=-1;
				while($j < $file_count)
				{
					$j++;
					$filen = $_FILES['upload_file']['name'][$j];
					
					$filen = iconv("EUC-KR", "UTF-8",$filen);
					if ($filen!="")
					{
						
						//target set
						$ext = str_replace('.', '', strrchr($filen, '.'));
						
						$path_file = $absolute_directory.'/attach/file/'.$get_lastid. '/' . md5($filen).'.'.$ext;
						$path_thumb = $absolute_directory.'/attach/thumb/'.$get_lastid. '/' . $filen;
						$path_reader = $absolute_directory.'/attach/reader/'.$get_lastid. '/' . $filen;
						
						//deny webshell
						if(!preg_match('/\.(php|html|cgi|jsp|php3|htm|war|asa|cdx|cer|asp|txt)(?:[\?\#].*)?$/i', $filen, $matches))
						{
							if(preg_match('/\.(jpg|gif|png|mp3|mp4|avi|zip|7z|rar)(?:[\?\#].*)?$/i', $filen, $matches))
							{
								if(move_uploaded_file($_FILES["upload_file"]['tmp_name']["$j"],$path_file))
								{
									//file query
									$oController->insertFileList($get_lastid, md5($filen).'.'.$ext, $filen);
									
									if($post_arr['board'] === 'index' && preg_match('/\.(jpg|jpeg|png|gif)(?:[\?\#].*)?$/i', $path_file, $matches))
									{
										//view thumbnail file set
										$ext = str_replace('.', '', strrchr($filen, '.'));
										$target_thumb_dir =  $absolute_directory.'/attach/thumb_index/'.$get_lastid.'/'.'view.'.$ext;
										
										if(!file_exists($target_thumb_dir))
										{
											//create thumbnail
											$oFunc->createThumbs($path_file,$target_thumb_dir,120,120);
											$oController->insertThumbnailListIndex($get_lastid, 'view.'.$ext);
										}
										/*
										//ext
										$ext = str_replace('.', '', strrchr($filen, '.'));
										$fname = $j.".".$ext;
										$path_thumb = $path_target_thumb. "/" . $fname;
										
										//craete thumbnail
										$oFunc->createThumbs($path_file,$path_thumb,100,150);
										$oFunc->createThumbs($path_file,$path_reader,1200,0);
										
										//thumb query
										$oController->insertThumbnailList($get_lastid, $fname);*/
									}
								}
							}
						}
					}	
				}
			}
		}
	}
}



	/**
	 * select sql
	 */



if(isset($get_arr['bd']))
{

	if(!isset($get_arr['page']))
	{
		$get_arr['page']=1;
	}
	
	//get page count
	$page_count = $oModel->getDocumentCountbyBoard($get_arr['bd']);
	$board_count = (int)ceil($page_count/$list_count);
	$board_count_cut = $oFunc->round_up((int)floor($page_count/$list_count),-1);

	//get page array
	$arr_page = $oModel->getPageArray($board_count, $page_count, $get_arr['page'], $list_count);
	
	//file list
	if(isset($get_arr['srl']))
	{
		if($get_arr['act']==="reader")
		{
			$file_list = $oModel->getFileListItems($get_arr['srl']);
		}
		else
		{
			$file_list = $oModel->getFileList($get_arr['srl']);
		}
	}
	
	//thumb list
	if(isset($get_arr['srl']))
	{
		$thumb_list = $oModel->getThumbList($get_arr['srl']);
	}
	
	//comment list
	if(isset($get_arr['srl']) && isset($get_arr['bd']))
	{
		$comment_list = $oModel->getCommentList($get_arr['bd'], $get_arr['srl']);
	}
	
	//board skin
	$board_skin = $oModel->getSkin($get_arr['bd']);
	$title .= $oModel->getBoardTitle($get_arr['bd']);
	
	if($get_arr['page']<0)
	{
		//page limit overflow
		$err_message = '잘못된 요청입니다.';
		$err_mode = TRUE;
		$board_list = array();
	}
	else
	{
		//board list
		if($sql_front_sort === TRUE)
		{
			$page_start = ($list_count * ($board_count-($get_arr['page'])));
			$page_end = $page_start + $list_count;
			$board_list = $oModel->getDocumentlistBetween($get_arr['bd'], $page_start, $page_end);
			$board_list = array_reverse($board_list);
			$page_start=$page_start+count($board_list) - (($get_arr['page'] - $board_count));
		}
		else
		{
			$page_start = $page_count - (($get_arr['page'])*$list_count);
			$page_end = $page_start + $list_count;
			$board_list = $oModel->getDocumentlistBetween($get_arr['bd'], $page_start, $page_end);
			$board_list = array_reverse($board_list);
			
			$page_start=$page_start+count($board_list) - (($get_arr['page'] - $board_count));
			if($page_start<0)
				$page_start = count($board_list);
		}
	}
	

	if(isset($get_arr['act']))
	{
		//reader
		if(($get_arr['act'] === "view" || $get_arr['act'] ==="reader") && isset($get_arr['srl']))
		{
			//get document item
			$document = $oModel->getDocumentItems($get_arr['srl']);
			$title .= ' - '.$document['title'];
			//not found
			if($document === NULL)
			{
				$err_message = '404 NOT FOUND';
				$err_mode = TRUE;
			}
			
			if($get_arr['act'] === view)
			{
				//update readed count
				$document['readed'] = $document['readed']+1;
				$readed_count = $document['readed'];
				$oController->UpdateReadedCount($readed_count, $get_arr['srl']);
			}
			
		}
	}

}
else
{
	//default title
	$title = $def_title;
}

//include template
if($ajax_mode === FALSE)
{
	if(isset($board_skin))
	{
		if(isset($get_arr['act']))
		{
			if($get_arr['act'] === 'write')
			{
				$target_template = ($absolute_directory.'/module/board/'.$board_skin.'/write.php');
			}
			elseif($get_arr['act'] === 'view')
			{
				$target_template = array($absolute_directory.'/module/board/'.$board_skin.'/read.php',dirname(__FILE__).'/module/board/'.$board_skin.'/board.php');
			}
			elseif($get_arr['act'] === 'reader')
			{
				$target_template = array($absolute_directory.'/module/board/'.$board_skin.'/reader.php');
			}
			else
			{
				$target_template = ($absolute_directory.'/module/board/'.$board_skin.'/board.php');
			}
		}
		else
		{
			$target_template = ($absolute_directory.'/module/board/'.$board_skin.'/board.php');
		}
	}
	else
	{
		if(isset($get_arr['act']))
		{
			if($get_arr['act'] === 'permit')
			{
				if($is_admin === FALSE)
				{
					$err_message = '권한이 없습니다.';
					$err_mode = TRUE;
				}
			}
		}
	}
	
	//layout
	if(isset($layout_skin))
	{
		require($absolute_directory.'/module/layout/'.$layout_skin.'/skin.php');
	}

}

//error
if($err_mode === TRUE)
{
	$target_template = ($absolute_directory.'/module/message/'.$message_skin.'/err.php');
}

?>
