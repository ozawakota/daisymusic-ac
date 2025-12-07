<div class="contact__forms">

<fieldset>
  <legend>お問い合わせ内容</legend>
  [select* your-select first_as_label "選択してください" "ピアノ" "弦楽器" "管楽器" "声楽" "その他"]

</fieldset>
<div class="flex">
<div class="col">
<fieldset>
  <legend>姓</legend>
  [text* your-firstname]
</fieldset>
<fieldset>
 <legend>名</legend>
 [text* your-lastname]
</fieldset>
</div>
<div class="col">
<fieldset>
  <legend>メールアドレス</legend>
  [email* your-email autocomplete:email] 
</fieldset>
</div>
</div>
<fieldset>
 <legend>電話番号</legend>
 [tel* your-tel]
</fieldset>
<fieldset>
 <legend>お問い合わせ内容</legend>
 [textarea* your-message]
</fieldset>
<div class="contact__forms--other">
  <p class="-title">レッスンのお申し込みご希望の方</p>
  <fieldset>
   <legend>専攻楽器（任意）</legend>
[select your-select-other first_as_label "選択してください" "ピアノ" "弦楽器" "管楽器" "声楽" "その他"]
  </fieldset>
<fieldset>
   <legend>あなたについて教えてください（任意）</legend>
  [radio your-radio use_label_element "初心者（3年未満）" "経験者（3年以上）" "受験生" "講師" "その他"]
  </fieldset>
</div>
<div class="contact__forms--submit">
<fieldset>
  [acceptance your-acceptance optional] <a href="https://mika-ojima.com/privacy-policy/" target="_blank">プライバシーポリシー</a>に同意します [/acceptance]
</fieldset>
<div class="-btn">
 [submit "送信"]

</div>
</div><!-- .contact__forms--submit -->
</div><!-- .contact__forms -->