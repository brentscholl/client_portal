<?php

namespace App\Console\Commands;

use App\Mail\NotifyClient;
use App\Models\Email;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Traits\EmailBuilder\EmailDataLoader;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DatePeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EmailSchedulerCommand extends Command
{
    use EmailDataLoader;

    public $email_signature;
    public $client;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stealth:email-scheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes email schedules and sends emails';

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
     * @return int
     */
    public function handle()
    {
        $email_templates = EmailTemplate::with(['client:id,title'])->where('schedule_email_to_send', 1)->get();

        foreach($email_templates as $email_template){
            if($this->is_send_date($email_template)){
                DB::beginTransaction();
                $this->email_signature = $email_template->email_signature;
                $this->client = $email_template->client;

                $this->loadData(json_decode($email_template->layout, true), null, $email_template->user);

                $recipients = User::hydrate(json_decode($email_template->recipients));
                // Create Email for DB record
                Email::create([
                    'client_id'         => $email_template->client_id,
                    'email_template_id' => $email_template->id,
                    'subject'           => $email_template->subject,
                    'recipients'        => json_encode($recipients->pluck('email')->toArray()),
                    'data'              => json_encode($this->data),
                    'sent_date'         => Carbon::now(),
                ]);

                // Queue mail to send
                \Mail::to($recipients)->queue(new NotifyClient($this->data, json_decode($email_template->layout, true), $email_template->subject));

                DB::commit();
            }
            if($email_template->ends !== 'never'){
                if($email_template->end_date->isToday()){
                    $email_template->update(['schedule_email_to_send' => 0]);
                }
            }
        }
    }

    /**
     * Figures out if the interval of the schedule has a date that landed on today
     * if it does we can send the email.
     * @param $email_template
     * @return false
     */
    public function is_send_date($email_template) {
        $start_date = $email_template->send_date;
        $end_date = Carbon::parse('tomorrow');

        switch ( $email_template->repeat ) {
            case('does-not-repeat'): // Does not repeat -------------------------------------------------
                return $start_date->isToday();
                break;

            case('daily'): // Daily --------------------------------------------------------------------
                $interval = CarbonInterval::make('1day');
                $period = new DatePeriod($start_date, $interval, $end_date);
                foreach ( $period as $dt ) {
                    if($dt->isToday()){
                        return $dt->isToday();
                        break;
                    }
                }
                break;

            case('weekly'): // Weekly ------------------------------------------------------------------
                $interval = CarbonInterval::make('1week');
                $period = new DatePeriod($start_date, $interval, $end_date);
                foreach ( $period as $dt ) {
                    if($dt->isToday()){
                        return $dt->isToday();
                        break;
                    }
                }
                break;

            case('monthly'): // Monthly ----------------------------------------------------------------

                $dayName = $start_date->isoFormat('dddd');
                switch ( $start_date->weekOfMonth ) {
                    case(1):
                        $dayNumberOfMonth = 'first';
                        break;
                    case(2):
                        $dayNumberOfMonth = 'second';
                        break;
                    case(3):
                        $dayNumberOfMonth = 'third';
                        break;
                    case(4):
                        $dayNumberOfMonth = 'fourth';
                        break;
                    case(5):
                        $dayNumberOfMonth = 'fifth';
                        break;
                }
                $interval = CarbonInterval::make('1 month');
                $period = new DatePeriod($start_date, $interval, $end_date);
                foreach ( $period as $dt ) {
                    if($dt->isToday()){
                        return $dt->isToday();
                        break;
                    }
                }
                break;

            case('weekday'): // Weekday --------------------------------------------------------------
                $interval = CarbonInterval::make('1day');
                $period = new DatePeriod($start_date, $interval, $end_date);
                foreach ( $period as $dt ) {
                    if ( $dt->dayOfWeek !== 0 && $dt->dayOfWeek !== 6 ) {
                        if($dt->isToday()){
                            return $dt->isToday();
                            break;
                        }
                    }
                }
                break;

            case('weekend'): // Weekend -------------------------------------------------------------
                $interval = CarbonInterval::make('1day');
                $period = new DatePeriod($start_date, $interval, $end_date);
                foreach ( $period as $dt ) {
                    if ( $dt->dayOfWeek !== 1 && $dt->dayOfWeek !== 2 && $dt->dayOfWeek !== 3 && $dt->dayOfWeek !== 4 && $dt->dayOfWeek !== 5 ) {
                        if($dt->isToday()){
                            return $dt->isToday();
                            break;
                        }
                    }
                }
                break;

            case('custom'): // Custom ---------------------------------------------------------------
                switch ( $email_template->repeats_every_type ) {
                    case('day'):
                        $interval = CarbonInterval::make($email_template->repeats_every_item . ' day');
                        $period = new DatePeriod($start_date, $interval, $end_date);
                        foreach ( $period as $dt ) {
                            if($dt->isToday()){
                                return $dt->isToday();
                                break;
                            }
                        }
                        break;
                    case('week'): // Custom = Week ----------------------------------------------------
                        $daysOfWeek = [];
                        $daysJson = json_decode($email_template->repeats_weekly_on);
                        // Get array of true days
                        foreach ( $daysJson as $key => $value ) {
                            if ( $value ) {
                                array_push($daysOfWeek, $key);
                            }
                        }
                        // Sort array in proper order of days of the week
                        $order = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                        usort($daysOfWeek, function ($a, $b) use ($order) {
                            $pos_a = array_search($a, $order);
                            $pos_b = array_search($b, $order);

                            return $pos_a - $pos_b;
                        });

                        $interval = CarbonInterval::make($email_template->repeats_every_item . ' week');
                        $period = new DatePeriod($start_date, $interval, $end_date);
                        foreach ( $period as $dt ) {
                            $firstDayOfWeek = Carbon::parse($dt)->startOfWeek()->addDays(-1);
                            $lastDayOfWeek = Carbon::parse($dt)->endOfWeek()->addDays(-1);
                            $intervalWeek = CarbonInterval::make('1day');
                            $periodWeek = new DatePeriod($firstDayOfWeek, $intervalWeek, $lastDayOfWeek);
                            foreach ( $periodWeek as $day ) {
                                if($day >= $start_date && $day <= $end_date ) {
                                    if($dt->isToday()){
                                        return $dt->isToday();
                                        break;
                                    }
                                }
                            }
                        }
                        break;
                    case('month'): // Custom = Month ---------------------------------------------------
                        switch($email_template->repeats_monthly_on){
                            case('date'): // Custom = Month = Date -----------------------------------------
                                $dayNumber = $start_date->format('d');
                                $interval = CarbonInterval::make($email_template->repeats_every_item . ' month');
                                $period = new DatePeriod($start_date, $interval, $end_date);
                                foreach ( $period as $dt ) {
                                    if($dt->isToday()){
                                        return $dt->isToday();
                                        break;
                                    }
                                }
                                break;

                            case('day'): // Custom = Month = Day -----------------------------------------
                                $dayName = $start_date->isoFormat('dddd');
                                switch ( $start_date->weekOfMonth ) {
                                    case(1):
                                        $dayNumberOfMonth = 'first';
                                        break;
                                    case(2):
                                        $dayNumberOfMonth = 'second';
                                        break;
                                    case(3):
                                        $dayNumberOfMonth = 'third';
                                        break;
                                    case(4):
                                        $dayNumberOfMonth = 'fourth';
                                        break;
                                    case(5):
                                        $dayNumberOfMonth = 'fifth';
                                        break;
                                }
                                $interval = CarbonInterval::make($email_template->repeats_every_item . ' month');
                                $period = new DatePeriod($start_date, $interval, $end_date);
                                foreach ( $period as $dt ) {
                                    if($dt->isToday()){
                                        return $dt->isToday();
                                        break;
                                    }
                                }
                                break;
                        }
                        break;
                }
                break;
        }
        return false;
    }
}
