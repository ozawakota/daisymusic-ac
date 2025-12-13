/**
 * Contact Form 7の任意フィールドバリデーション無効化
 */
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('.wpcf7-form');

  if (!form) return;

  // radio-153フィールドを任意にする
  const radioFields = form.querySelectorAll('input[name="radio-153"]');
  radioFields.forEach(function(radio) {
    radio.removeAttribute('aria-required');
    radio.closest('.wpcf7-form-control-wrap').removeAttribute('data-required');
  });

  // your-select-otherフィールドを任意にする
  const selectField = form.querySelector('select[name="your-select-other"]');
  if (selectField) {
    selectField.removeAttribute('aria-required');
    selectField.closest('.wpcf7-form-control-wrap').removeAttribute('data-required');
  }

  // フォーム送信時のバリデーションをカスタマイズ
  form.addEventListener('submit', function(e) {
    // 任意フィールドのエラーメッセージを削除
    const optionalFields = ['radio-153', 'your-select-other'];
    optionalFields.forEach(function(fieldName) {
      const wrapper = form.querySelector('[data-name="' + fieldName + '"]');
      if (wrapper) {
        const errorTip = wrapper.querySelector('.wpcf7-not-valid-tip');
        if (errorTip) {
          errorTip.remove();
        }
        wrapper.classList.remove('wpcf7-not-valid');
      }
    });
  });
});
