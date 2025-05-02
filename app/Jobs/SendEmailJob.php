<?php 

namespace App\Jobs;

use App\Mail\NewSaleMail;
use App\Mail\UpdatedSaleMail;
use App\Mail\WelcomeMail;
use App\Mail\WelcomeSellerMail;
use App\Models\Sale;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail as MailFacade;

class SendEmailJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected $emailType;
    protected $user;
    protected $seller;
    protected $sale;
    protected $oldSale;

    public function __construct($emailType, User $user, Seller $seller, Sale $sale = null, Sale $oldSale = null)
    {
        $this->emailType = $emailType;
        $this->user = $user;
        $this->seller = $seller;
        $this->sale = $sale;
        $this->oldSale = $oldSale;
    }

    public function handle()
    {
        switch ($this->emailType) {
            case 'welcome':
                MailFacade::to($this->user->email)->send(new WelcomeMail($this->user));
                break;

            case 'welcome_seller':
                MailFacade::to($this->seller->email)->send(new WelcomeSellerMail($this->user, $this->seller));
                break;

            case 'new_sale':
                MailFacade::to($this->seller->email)->send(new NewSaleMail($this->user, $this->seller, $this->sale));
                break;

            case 'updated_sale':
                MailFacade::to($this->seller->email)->send(new UpdatedSaleMail($this->user, $this->seller, $this->oldSale, $this->sale));
                break;
        }
    }
}
