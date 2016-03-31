<?php
class resizeimage
{

	var $type; //图片类型
	var $width; //实际宽度
	var $height; //实际高度
	var $resize_width; //改变后的宽度
	var $resize_height; //改变后的高度
	var $cut; //是否裁图
	var $srcimg; //源图象
	var $dstimg; //目标图象地址
	var $im; //临时创建的图象

	function resizeimage($img, $wid, $hei, $c,$dstpath){
		$this->srcimg = $img;
		$this->resize_width = $wid;
		$this->resize_height = $hei;
		$this->cut = $c;
		//图片的类型

		$this->type = strtolower(substr(strrchr($this->srcimg,"."),1));

		//初始化图象
		$this->initi_img();
		//目标图象地址
		$this -> dst_img($dstpath);
		//--
		$this->width = imagesx($this->im);
		$this->height = imagesy($this->im);
		//生成图象
		$this->newimg();
		ImageDestroy ($this->im);
	}
	function newimg(){
		//改变后的图象的比例
		$resize_ratio = ($this->resize_width)/($this->resize_height);
		//实际图象的比例
		$ratio = ($this->width)/($this->height);
		if(($this->cut)=="1"){//裁图
			if($ratio>=$resize_ratio){//高度优先
				$newimg = imagecreatetruecolor($this->resize_width,$this->resize_height);
				imagecopyresampled($newimg, $this->im, 0, 0, 0, 0, $this->resize_width,$this->resize_height, (($this->height)*$resize_ratio), $this->height);
				ImageJpeg ($newimg,$this->dstimg);
			}
			if($ratio<$resize_ratio){//宽度优先
				$newimg = imagecreatetruecolor($this->resize_width,$this->resize_height);
				imagecopyresampled($newimg, $this->im, 0, 0, 0, 0, $this->resize_width, $this->resize_height, $this->width, (($this->width)/$resize_ratio));
				ImageJpeg ($newimg,$this->dstimg);
			}
		}
		else{//不裁图
			if($ratio>=$resize_ratio){
				$newimg = imagecreatetruecolor($this->resize_width,($this->resize_width)/$ratio);
				imagecopyresampled($newimg, $this->im, 0, 0, 0, 0, $this->resize_width, ($this->resize_width)/$ratio, $this->width, $this->height);
				ImageJpeg ($newimg,$this->dstimg);
			}
			if($ratio<$resize_ratio){
				$newimg = imagecreatetruecolor(($this->resize_height)*$ratio,$this->resize_height);
				imagecopyresampled($newimg, $this->im, 0, 0, 0, 0, ($this->resize_height)*$ratio, $this->resize_height, $this->width, $this->height);
				ImageJpeg ($newimg,$this->dstimg);
			}
		}
	}
	//初始化图象
	function initi_img(){
		if($this->type === "jpg"){
			$this->im = imagecreatefromjpeg($this->srcimg);
		}
		if($this->type === "gif"){
			$this->im = imagecreatefromgif($this->srcimg);
		}
		if($this->type === "png"){
			$this->im = imagecreatefrompng($this->srcimg);
		}
	}
	//图象目标地址
	function dst_img($dstpath){
		$full_length  = strlen($this->srcimg);

		$type_length  = strlen($this->type);
		$name_length  = $full_length-$type_length;

		$name		  = substr($this->srcimg,0,$name_length-1);
		$this->dstimg = $dstpath;
	}
}
?>