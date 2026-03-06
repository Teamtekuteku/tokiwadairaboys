<?php get_header(); ?>

<main class="l-main-content__contact">
  <section class="p-contact-page">
    <div class="l-section__contact">
      <div class="c-title">
        <h2 class="c-title__en">CONTACT</h2>
        <span class="c-title__ja">体験・見学お申し込み</span>
      </div>

      <p class="p-contact-page__lead">
        以下のフォームに必要事項をご記入の上、送信してください。<br />
        内容を確認後、担当者より折り返しご連絡いたします。
      </p>

      <div class="p-contact-page__form-wrapper">
        <iframe
          src="https://docs.google.com/forms/d/e/1FAIpQLScvtvK3pE5ArdsIKgPq8j1ZF2Jan_L1Zt6nrCP6G0-OegVlhg/viewform?embedded=true"
          width="100%" height="1600" frameborder="0" marginheight="0" marginwidth="0"
          id="js-form-iframe">読み込んでいます…</iframe>
      </div>
    </div>
  </section>

  <div id="js-modal" class="c-modal">
    <div class="c-modal__inner">
      <div class="c-modal__content">
        <h3 class="c-modal__title">送信完了しました</h3>
        <p class="c-modal__text">
          体験・見学のお申し込みありがとうございます。<br />
          内容を確認し、担当者より3日以内にご連絡いたします。
        </p>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="c-button c-button--red js-modal-close">トップページへ戻る</a>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>