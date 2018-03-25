<?php

namespace App\Jobs;

//use App\Services\OptionService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data = [];
    protected $template = '';
    protected $to = '';
    protected $subject = '';
//    protected $optionService;

    /**
     * Create a new job instance.
     *
     * @param string $template
     * @param string $subject
     * @param string $to
     * @param array $data
     * @return void
     */
    public function __construct($template, $subject, $to, $data)
    {
        $this->data = $data;
        $this->template = $template;
        $this->to = $to;
        $this->subject = $subject;
//        $this->optionService = app(OptionService::class);

        $this->data['locale'] = session('lang');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!empty($this->data['locale'])) {
            App::setLocale($this->data['locale']);
        }

//        \Mail::send($this->template, $this->data, function ($m) {
//            $m->from('from email', 'from');
//            $m->to($this->to)->subject($this->subject);
//        });
    }
}
