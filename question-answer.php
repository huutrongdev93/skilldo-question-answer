<?php
use JetBrains\PhpStorm\NoReturn;

const QA_NAME = 'question-answer';

const QA_KEY = 'question-answer';

define('QA_PATH', Path::plugin(QA_NAME));

class Question_Answer {

    private string $name = 'question_answer';

    function __construct() {

        $this->loadDependencies();

        new QA_Taxonomy();

        if(Admin::is()) {
            new Question_Answer_Roles();
        }
    }

    public function active(): void
    {
        QA_Activator::activate();
    }

    public function restart(): void
    {
        QA_Activator::templateFile();
    }

    public function uninstall() {}

    private function loadDependencies(): void
    {
        require_once QA_PATH.'/includes/product.php';
        require_once QA_PATH.'/includes/active.php';
        require_once QA_PATH.'/includes/taxonomy.php';
        require_once QA_PATH.'/includes/schema.php';
        require_once QA_PATH.'/includes/roles.php';
        require_once QA_PATH.'/includes/template.php';
    }
}

new Question_Answer();

if (!function_exists('ajax_question_save')) {

    #[NoReturn]
    function ajax_question_save(\SkillDo\Http\Request $request, $model): void
    {
        if ($request->isMethod('post')) {

            $validate = $request->validate(QuestionTemplate::form());

            if ($validate->fails()) {
                response()->error($validate->errors());
            }

            $name  = trim($request->input('name'));

            $email = trim($request->input('email'));

            $question = trim($request->input('question'));

            if(Str::length($question) <= 20) {
                response()->error(trans('question.form.error.length'));
            }

            $data = [
                'title'     => $name,
                'excerpt'   => $email,
                'content'   => $question,
                'public'    => 0,
                'status'    => 1,
                'post_type' => 'question'
            ];

            $res  = Posts::insert($data);

            if(!is_skd_error($res)) {

                \SkillDo\Cache::delete('question_count_new');

                response()->success('question.form.success');
            }
        }

        response()->error(trans('ajax.add.error'));
    }
    Ajax::client('ajax_question_save');
}


