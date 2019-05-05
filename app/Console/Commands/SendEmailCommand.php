<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $content = '测试测试';
        $toMail  = 'xb0004@kksnail.com';
        $subject = '测试测试';

        // 发送 纯文本邮件
        Mail::raw($content, function ($message) use ($toMail, $subject) {
            $message->subject($subject);
            $message->to($toMail);
        });
    }
}
