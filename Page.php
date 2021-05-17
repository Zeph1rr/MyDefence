<?php

class Page {

	public $message = [];
	public $error = [];

	public function message($text)
    {
        $this->message []= $text;
	}

	public function error($text, $sqlError='')
    {
        if ($sqlError) {
        	$text .= '<pre class="small" style="margin-top:10px;">'.htmlspecialchars($sqlError).'</pre>';
        }
        $this->error []= $text;
	}

    public function printMessages($redirect='')
    {
        if ($this->message) {
            echo '<div class="alert alert-info">'.implode('<br />', $this->message).'</div>';
        }
        if ($this->error) {
            echo '<div class="alert alert-danger">'.implode('<br />', $this->error).'</div>';
        }
     }

    public function hasMessages()
    {
        return $this->message || $this->error;
    }

    public function redirectBack()
    {
        header('Location: '.$_SERVER['HTTP_REFERER']);
        exit;
    }

    public function redirect($url, $delay=0)
    {
        if ($delay) {
            ?>
            <script type="text/javascript">
            setTimeout(function() {
                location = '<?=$url?>'
            }, <?=$delay * 1000?>);
            </script>
            <?php
        } else {
            header('Location: '.$url);
            exit;
        }
    }
}