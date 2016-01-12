<?php

class Model
{
	
	protected $PDO;
	
    public function __construct($PDO)
    {
		$this->pdo = $PDO;
    }

	function getCaptchaStatus()
	{
		if(isset($post_arr['captcha_code']) && isset($_SESSION['captcha_code']))
		{
			if($post_arr['captcha_code'] === $_SESSION['captcha_code'])
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
	}
	
	function getPageArray($board_count, $document_count, $get_page)
	{

		$arr_page = array();
		
		//get first page
		if($board_count === 0)
		{
			$board_count = 1;
		}

		if($get_page > $board_count-9)
		{
			$pg_index = $board_count - 10;
		}
		elseif($get_page > 5)
		{
			$pg_index = $get_page - 6;
		}
		
		$pg_x = 0;
		while($pg_x<10)
		{
			if(isset($pg_index))
			{
				$pg_x++;
				$pg_insert = $pg_x+$pg_index;
				if($pg_insert>0)
				{
					array_push($arr_page,$pg_insert);
				}
			}
			else
			{
				$pg_x++;
				array_push($arr_page,$pg_x);
			}
		}
		
		return $arr_page;
	}
	
	function getDocumentlistBetween($get_board, $page_start, $page_end)
	{
		$sth = $this->pdo->prepare("SELECT * FROM bddoc WHERE module = :bd AND srl_bd BETWEEN :pgx AND :pgcount ORDER BY srl_bd asc");
		$sth->bindParam(':bd', $get_board, PDO::PARAM_STR);
		$sth->bindParam(':pgx', $page_start, PDO::PARAM_INT);
		$sth->bindParam(':pgcount', $page_end, PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetchAll();
	}
	
	function getVotedCount($get_serial)
	{
		$sth = $this->pdo->prepare("SELECT voted FROM bddoc WHERE srl = :srl");
		$sth->bindParam(':srl', $get_serial);
		$sth->execute();
		return $sth->fetch();
	}
	
	function getBlamedCount($get_serial)
	{
		$sth = $this->pdo->prepare("SELECT blamed FROM bddoc WHERE srl = :srl");
		$sth->bindParam(':srl', $get_serial);
		$sth->execute();
		return $sth->fetch();
	}
	
	function getCommentCount($get_board, $get_serial)
	{
		$sth = $this->pdo->prepare("SELECT count(*) FROM cmt_list WHERE module = :bd AND document_srl = :srl");
		$sth->bindParam(':bd', $get_board);
		$sth->bindParam(':srl', $get_serial);
		$sth->execute();
		return $sth->fetch();
	}
	
	function getParentCommentList($get_board, $get_serial)
	{
		$sth = $this->pdo->prepare("SELECT * FROM cmt_list WHERE parent_srl = 0 AND module = :bd AND document_srl = :srl");
		$sth->bindParam(':bd', $get_board);
		$sth->bindParam(':srl', $get_serial,PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetchAll();
	}
	
	function getCommentList($get_board, $get_serial)
	{
		$sth = $this->pdo->prepare("SELECT * FROM cmt_list WHERE module = :bd AND document_srl = :srl");
		$sth->bindParam(':bd', $get_board);
		$sth->bindParam(':srl', $get_serial,PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetchAll();
	}
	
	function getThumbList($get_serial)
	{
		$sth = $this->pdo->prepare("SELECT files FROM bdthumb WHERE target = :url");
		$sth->bindParam(':url', $get_serial,PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetchAll();
	}
	
	function getFileList($get_serial)
	{
		$sth = $this->pdo->prepare("SELECT * FROM bdfiles WHERE target = :url");
		$sth->bindParam(':url', $get_serial,PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetchAll();
	}
	
	function getFileListItems($get_serial)
	{
		$sth = $this->pdo->prepare("SELECT files FROM bdfiles WHERE target = :url");
		$sth->bindParam(':url', $get_serial,PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetchAll();
	}
	
	function getDocumentItems($get_serial)
	{
		$sth = $this->pdo->prepare("SELECT * FROM bddoc WHERE srl = :srl");
		$sth->bindParam(':srl',$get_serial);
		$sth->execute();
		return $sth->fetch();
	}
	
	function getTableSequence()
	{
		$sth = $this->pdo->prepare("SELECT srl FROM bddoc ORDER BY srl DESC");
		$sth->execute();
		$result = $sth->fetch();
		return $result['srl']+1;
	}
	
	function getBoardSequence($get_board)
	{
		$sth = $this->pdo->prepare("SELECT srl_bd FROM bddoc WHERE module = :bd ORDER BY srl_bd desc");
		$sth->bindParam(':bd',$get_board);
		$sth->execute();
		$result = $sth->fetch();
		return $result['srl_bd']+1;
	}
	
	function getBoardTitle($get_board)
	{
		$sth = $this->pdo->prepare("SELECT title FROM bdlist WHERE bdname = :bd");
		$sth->bindParam(':bd', $get_board, PDO::PARAM_STR);
		$sth->execute();
		$result = $sth->fetch();
		return $result['title'];
	}

	function getSkin($get_board)
	{
		$sth = $this->pdo->prepare("SELECT skin FROM bdlist WHERE bdname = :bd");
		$sth->bindParam(':bd', $get_board, PDO::PARAM_STR);
		$sth->execute();
		$result = $sth->fetch();
		return $result[0];
	}
	
	function getDocumentCountbyBoard($get_board)
	{
		//board page count
		$sth = $this->pdo->prepare("SELECT count(*) FROM bddoc WHERE module = :bd");
		$sth->bindParam(':bd', $get_board, PDO::PARAM_STR);
		$sth->execute();
		$std_count = $sth->fetch();
		return $std_count[0];
	}
	
}
