<?php
$configFile=dirname(__DIR__).'/config/config.php';
$sent=false;$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!is_file($configFile)){http_response_code(503);$error='現在、お問い合わせを受け付けられません。';}
  elseif(!empty($_POST['website']??'')){$sent=true;}
  else{
    require dirname(__DIR__).'/app/bootstrap.php';
    $name=trim((string)($_POST['name']??''));$email=trim((string)($_POST['email']??''));$message=trim((string)($_POST['message']??''));
    if($name===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||mb_strlen($message)<10){$error='入力内容を確認してください。';}
    else{
      $ip=(string)($_SERVER['REMOTE_ADDR']??'');
      $hash=$ip!==''?hash('sha256',$ip):null;
      $stmt=$pdo->prepare("INSERT INTO contact_messages(name,email,message,ip_hash) VALUES(?,?,?,?)");
      $stmt->execute([$name,$email,$message,$hash]);$sent=true;
    }
  }
}
$pageTitle='CONTACT｜エギングギアラボ';$pageDescription='エギングギアラボへのお問い合わせ。';require __DIR__.'/partials/header.php';?>
<main id="main"><section class="page-hero"><img class="page-art" src="/assets/visuals/hero-depth.svg" alt=""><div><span class="kicker">CONTACT / 07</span><h1>話そう。</h1><p>掲載内容の修正、メーカー・ショップからの情報提供、サイトへのご意見はこちらから。</p></div></section><section class="section">
<?php if($sent):?><p class="result-panel"><strong>送信しました。</strong><br>内容を確認後、必要に応じて返信します。</p>
<?php else:?><form class="contact-form" method="post" action="/contact.php">
<input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px">
<label>お名前<input required maxlength="120" type="text" name="name" autocomplete="name" value="<?=htmlspecialchars($_POST['name']??'',ENT_QUOTES,'UTF-8')?>"></label>
<label>メール<input required maxlength="255" type="email" name="email" autocomplete="email" value="<?=htmlspecialchars($_POST['email']??'',ENT_QUOTES,'UTF-8')?>"></label>
<label>内容<textarea required minlength="10" maxlength="5000" name="message"><?=htmlspecialchars($_POST['message']??'',ENT_QUOTES,'UTF-8')?></textarea></label>
<?php if($error):?><p role="alert"><?=htmlspecialchars($error,ENT_QUOTES,'UTF-8')?></p><?php endif;?>
<button class="cta cta--primary" type="submit">送信する</button></form><?php endif;?>
</section></main><?php require __DIR__.'/partials/footer.php';?>