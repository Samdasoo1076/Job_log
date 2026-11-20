<?php if (empty($seoHeadInline)): ?><!DOCTYPE html>
<html lang="ko">
<head><?php endif; ?>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="format-detection" content="telephone=no" />
<?php if (empty($seoHeadInline)): ?><meta name="viewport" content="width=device-width,initial-scale=1.0" /><?php endif; ?>
<meta name="description" content="<?=htmlspecialchars($seo_description, ENT_QUOTES, 'UTF-8')?>" />
<meta property="og:type" content="article" />
<meta property="og:title" content="<?=htmlspecialchars($seo_title, ENT_QUOTES, 'UTF-8')?>" />
<meta property="og:url" content="<?=htmlspecialchars($g_url, ENT_QUOTES, 'UTF-8')?>" />
<meta property="og:site_name" content="<?=$g_front_title?>" />
<meta property="og:description" content="<?=htmlspecialchars($seo_description, ENT_QUOTES, 'UTF-8')?>" />
<meta property="og:image" content="<?=$seo_og_image ?? ''?>" />
<meta property="fb:admins" content="" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="<?=htmlspecialchars($seo_title, ENT_QUOTES, 'UTF-8')?>" />
<meta name="twitter:description" content="<?=htmlspecialchars($seo_description, ENT_QUOTES, 'UTF-8')?>" />
<meta name="twitter:image" content="<?=$seo_og_image ?? ''?>" />
<meta name="twitter:creator" content="<?=$g_front_title?>" />
<meta name="keywords" content="<?=htmlspecialchars($seo_keywords, ENT_QUOTES, 'UTF-8')?>" />

<link rel="shortcut icon" href="../assets/icon/favicon.svg" />
<link rel="apple-touch-icon" href="../assets/icon/favicon.svg" />

<link rel="icon" href="../assets/icon/favicon.svg" type="image/png">
<link rel="shortcut icon" href="../assets/icon/favicon.svg" type="image/png">

<title><?=htmlspecialchars($seo_title, ENT_QUOTES, 'UTF-8')?></title>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-T2JP8HJ850"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-T2JP8HJ850');
</script>
<!-- Google tag (gtag.js) -->

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5133707720452623"
     crossorigin="anonymous"></script>
     <!-- Microsoft Clarity -->
     <script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "x7rgfa6vqp");
</script>
     <!-- Microsoft Clarity -->
<!-- 페이지 스크립트 영역 -->
<script></script>

<?php if (empty($seoHeadInline)): ?></head><?php endif; ?>
