<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BookEvent;
use Carbon\Carbon;

class UpdateEventStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing event statuses based on their dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = BookEvent::all();
        $today = Carbon::today();
        
        $this->info('Updating event statuses...');
        
        foreach ($events as $event) {
            $eventDate = Carbon::parse($event->date_evenement);
            
            if ($eventDate->isSameDay($today)) {
                $status = 'en_cours';
            } elseif ($eventDate->isPast()) {
                $status = 'termine';
            } else {
                $status = 'a_venir';
            }
            
            $event->update(['status' => $status]);
            $this->line("Updated event '{$event->titre}' to status: {$status}");
        }
        
        $this->info('Event statuses updated successfully!');
    }
}
