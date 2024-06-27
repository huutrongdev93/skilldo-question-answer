<div class="question-section-form">
    <div class="header-title"><h3 class="header">{{trans('question.form.title')}}</h3></div>
    {!! $form->open() !!}
        {!! Admin::loading() !!}
        {!! $form->html() !!}
        <button type="submit" class="btn btn-effect-default btn-theme btn-block">{{trans('question.form.button.send')}}</button>
    {!! $form->close() !!}
</div>
<script>
	function questionFormSubmit(element) {

		let loader = element.find('.loading');

		let data = element.serializeJSON();

		data.action = 'ajax_question_save';

		loader.show();

		request.post(ajax, data).then(function (response) {

			loader.hide();

			SkilldoMessage.response(response);

			if (response.status === 'success') {

				$('#js_question_form').trigger('reset');
			}
		})

		return false;
	}
</script>