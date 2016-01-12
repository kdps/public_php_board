<?php

class Controller
{
	
	protected $PDO;
	
    public function __construct($PDO)
    {
		$this->pdo = $PDO;
    }
	
	/**
	 * upload ajax file
	 *
	 * @param string $sub_directory
	 * @param $_files $upload_file
	 * @param string $path_ajax
	 * @param INT $ajax_id
	 * @return json
	 */
	function UploadAjaxFileJSON($sub_directory, $upload_file, $path_ajax, $ajax_id)
	{
		$file_count = count($_FILES[$upload_file]['name']);
		if($file_count>0)
		{
			if(!is_dir($path_ajax))
			{
				mkdir($path_ajax,0755);
			}
		
			$j=0;
			while($j < $file_count)
			{
				$filen = $_FILES[$upload_file]['name'][$j];
				if(!preg_match('/\.(php|html|cgi|jsp|php3|htm|war|asa|cdx|cer|asp|txt)(?:[\?\#].*)?$/i', $filen, $matches))
				{
					if(preg_match('/\.(jpg|gif|png|mp3|mp4|avi|zip|7z|rar)(?:[\?\#].*)?$/i', $filen, $matches))
					{
						if($filen!="")
						{
							$target_ajax = $path_ajax.'/'.$filen;
							if(move_uploaded_file($_FILES[$upload_file]['tmp_name'][$j],$target_ajax))
							{
								$sth = $this->pdo->prepare("INSERT INTO bdfiles (target, files) VALUES (:target, :files)");
								$sth->bindParam(':target', $ajax_id, PDO::PARAM_INT);
								$sth->bindParam(':files', $_FILES[$upload_file]['name']["$j"], PDO::PARAM_STR);
								$sth->execute();
							}
							
							$ajax_array = array("url"=>'"'.$sub_directory.'/attach/file/'.$ajax_id.'/'.$filen.'"',"name"=>$filen);
							return json_encode($ajax_array);
						}
					}
				}
				$j++;
			}
		}
	}
	
	/**
	 * upload document blamed count
	 *
	 * @param int $voted_count
	 * @param int $get_serial
	 */
	function UpdateBlamedCount($voted_count, $get_serial)
	{
		if(!$_SESSION['blamed_document'][$get_serial])
		{
			$_SESSION['blamed_document'][$get_serial] = TRUE;
			$sth = $this->pdo->prepare("UPDATE bddoc SET blamed = :count WHERE srl = :srl");
			$sth->bindParam(':count', $voted_count, PDO::PARAM_INT);
			$sth->bindParam(':srl', $get_serial, PDO::PARAM_INT);
			$sth->execute();
			return TRUE;
		}
	}
	
	/**
	 * upload document voted count
	 *
	 * @param int $voted_count
	 * @param int $get_serial
	 */
	function UpdateVotedCount($voted_count, $get_serial)
	{
		if(!$_SESSION['voted_document'][$get_serial])
		{
			$_SESSION['voted_document'][$get_serial] = TRUE;
			$sth = $this->pdo->prepare("UPDATE bddoc SET voted = :count WHERE srl = :srl");
			$sth->bindParam(':count', $voted_count, PDO::PARAM_INT);
			$sth->bindParam(':srl', $get_serial, PDO::PARAM_INT);
			$sth->execute();
			return TRUE;
		}
	}
	
	/**
	 * upload document readed count
	 *
	 * @param int $readed_count
	 * @param int $get_serial
	 */
	function UpdateReadedCount($readed_count, $get_serial)
	{
		if(!$_SESSION['readed_document'][$get_serial])
		{
			$_SESSION['readed_document'][$get_serial] = TRUE;
			$sth = $this->pdo->prepare("UPDATE bddoc SET readed = :count WHERE srl = :srl");
			$sth->bindParam(':count', $readed_count, PDO::PARAM_INT);
			$sth->bindParam(':srl', $get_serial, PDO::PARAM_INT);
			$sth->execute();
		}
	}
	
	/**
	 * insert member
	 *
	 * @param string $user_id
	 * @param string $password
	 * @param string $nickname
	 * @param string $isadmin
	 * @param string $minfo
	 */
	function insertMember($user_id, $password, $nickname, $isadmin, $minfo)
	{
		$password =  password_hash($password, PASSWORD_DEFAULT); 
		$sth = $this->pdo->prepare("INSERT INTO mlist (user_id, password, nick_name, is_admin, minfo) VALUES (:userid, :password, :nickname, :isadmin, :minfo)");
		$sth->bindParam(':userid', $user_id ,PDO::PARAM_STR);
		$sth->bindParam(':password', $password, PDO::PARAM_STR);
		$sth->bindParam(':nickname', $nickname, PDO::PARAM_STR);
		$sth->bindParam(':isadmin', $isadmin, PDO::PARAM_STR);
		$sth->bindParam(':minfo', $minfo, PDO::PARAM_STR);
		$sth->execute();
	}
	
	/**
	 * insert thumbnail index sql
	 *
	 * @param int $target
	 * @param string $files
	 */
	function insertThumbnailListIndex($target, $files)
	{
		$sth = $this->pdo->prepare("INSERT INTO thumb_index (target, files) VALUES (:target, :files)");
		$sth->bindParam(':target', $target, PDO::PARAM_INT);
		$sth->bindParam(':files', $files, PDO::PARAM_STR);
		$sth->execute();
	}
	
	/**
	 * insert thumbnail sql
	 *
	 * @param int $target
	 * @param string $files
	 */
	function insertThumbnailList($target, $files)
	{
		$sth = $this->pdo->prepare("INSERT INTO bdthumb (target, files) VALUES (:target, :files)");
		$sth->bindParam(':target', $target, PDO::PARAM_INT);
		$sth->bindParam(':files', $files, PDO::PARAM_STR);
		$sth->execute();
	}
	
	/**
	 * insert filelist sql
	 *
	 * @param int $target
	 * @param string $files
	 */
	function insertFileList($target, $files, $origin)
	{
		$sth = $this->pdo->prepare("INSERT INTO bdfiles (target, files, origin) VALUES (:target, :files, :origin)");
		$sth->bindParam(':target', $target, PDO::PARAM_INT);
		$sth->bindParam(':files', $files, PDO::PARAM_STR);
		$sth->bindParam(':origin', $origin, PDO::PARAM_STR);
		$sth->execute();
	}
	
	/**
	 * insert comment
	 *
	 * @param string $get_board
	 * @param string $post_content
	 * @param string $post_nick
	 * @param int $post_serial
	 */
	function insertReplyComment($get_board, $post_content, $post_nick, $post_serial, $parent)
	{
		if(isset($post_content) && isset($post_nick))
		{
			$sth = $this->pdo->prepare("INSERT INTO cmt_list (content, module, document_srl, nick_name, parent_srl) VALUES (:content, :module, :srl, :nickname, :parent)");
			$sth->bindParam(':module', $get_board, PDO::PARAM_STR);
			$sth->bindParam(':content', $post_content, PDO::PARAM_STR);
			$sth->bindParam(':nickname', $post_nick, PDO::PARAM_STR);
			$sth->bindParam(':srl', $post_serial, PDO::PARAM_INT);
			$sth->bindParam(':parent', $parent, PDO::PARAM_INT);
			$sth->execute();
		}
	}
	
	/**
	 * insert comment
	 *
	 * @param string $get_board
	 * @param string $post_content
	 * @param string $post_nick
	 * @param int $post_serial
	 */
	function insertComment($get_board, $post_content, $post_nick, $post_serial)
	{
		if(isset($post_content) && isset($post_nick))
		{
			$sth = $this->pdo->prepare("INSERT INTO cmt_list (content, module, document_srl, nick_name) VALUES (:content, :module, :srl, :nickname)");
			$sth->bindParam(':module', $get_board, PDO::PARAM_STR);
			$sth->bindParam(':content', $post_content, PDO::PARAM_STR);
			$sth->bindParam(':nickname', $post_nick, PDO::PARAM_STR);
			$sth->bindParam(':srl', $post_serial, PDO::PARAM_INT);
			$sth->execute();
		}
	}
	
	/**
	 * insert document
	 *
	 * @param string $post_title
	 * @param string $post_content
	 * @param string $post_date
	 * @param string $nickname
	 * @param string $post_board
	 * @param int $get_category
	 * @param int $board_serial
	 */
	function insertDocument($post_title, $post_content, $post_date, $nickname, $post_board, $get_category, $board_serial)
	{
		if(isset($post_title) && isset($post_content) && isset($nickname))
		{
			$sth = $this->pdo->prepare("INSERT INTO bddoc (title, content, nick_name, module, regdate, category, srl_bd) VALUES (:title, :content, :nick_name, :module, :date, :category, :srlbd)");
			$sth->bindParam(':title', $post_title, PDO::PARAM_STR);
			$sth->bindParam(':content', $post_content, PDO::PARAM_STR);
			$sth->bindParam(':nick_name', $nickname, PDO::PARAM_STR);
			$sth->bindParam(':module', $post_board, PDO::PARAM_STR);
			$sth->bindParam(':date', $post_date, PDO::PARAM_STR);
			$sth->bindParam(':category', $get_category, PDO::PARAM_INT);
			$sth->bindParam(':srlbd', $board_serial, PDO::PARAM_INT);
			$sth->execute();
		}
	}
}
