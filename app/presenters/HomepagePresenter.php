<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Utils\DateTime;
use App\Repositories\FeedingRepository;
use App\Repositories\CleaningRepository;

class HomepagePresenter extends BasePresenter
{
    /** @var FeedingRepository @inject */
    public $feedingRepository;

    /** @var CleaningRepository @inject */
    public $cleaningRepository;

    public function startup()
    {
        parent::startup();
        if ( ! $this->user->isLoggedIn()) {
            $this->redirect(0,'Sign:in');
        }
    }

	public function renderDefault()
	{
	    $this->template->today = new DateTime();
        $this->template->feedings = $this->feedingRepository->findUserFeedings($this->user->getId());
        $this->template->cleanings = $this->cleaningRepository->findUserCleanings($this->user->getId());
	}

}
