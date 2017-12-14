<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Showtimes Controller
 *
 *
 * @method \App\Model\Entity\Showtime[] paginate($object = null, array $settings = [])
 */
class ShowtimesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        
        $this->paginate = [
            'contain' => ['Movies', 'Rooms']
        ];


        $showtimes = $this->paginate($this->Showtimes);
        
        $this->set(compact('showtimes'));
        $this->set('_serialize', ['showtimes']);
        
    
           
    }

    /**
     * View method
     *
     * @param string|null $id Showtime id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        
        $showtime = $this->Showtimes->get($id, [
            'contain' => ['Movies','Rooms']
        ]);
        
        $this->set('showtime', $showtime);
        $this->set(compact(
            'movies',
            'rooms'
        ));
        $this->set('_serialize', ['showtime']);
        
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $movies = $this->Showtimes->Movies->find('list');
        $rooms = $this->Showtimes->Rooms->find('list');
        $showtime = $this->Showtimes->newEntity();
        if ($this->request->is('post')) {
            $showtime = $this->Showtimes->patchEntity($showtime, $this->request->getData());
            if ($this->Showtimes->save($showtime)) {
                $this->Flash->success(__('The showtime has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The showtime could not be saved. Please, try again.'));
        }
        
        $this->set(compact(
            'showtime',
            'movies',
            'rooms'
        ));
        $this->set('_serialize', ['showtime']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Showtime id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $movies = $this->Showtimes->Movies->find('list');
        $rooms = $this->Showtimes->Rooms->find('list');
        $showtime = $this->Showtimes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $showtime = $this->Showtimes->patchEntity($showtime, $this->request->getData());
            if ($this->Showtimes->save($showtime)) {
                $this->Flash->success(__('The showtime has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The showtime could not be saved. Please, try again.'));
        }
        $this->set(compact(
            'showtime',
            'movies',
            'rooms'
        ));
        $this->set('_serialize', ['showtime']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Showtime id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $showtime = $this->Showtimes->get($id);
        if ($this->Showtimes->delete($showtime)) {
            $this->Flash->success(__('The showtime has been deleted.'));
        } else {
            $this->Flash->error(__('The showtime could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    public function planning($id = null){
        
        $rooms = $this->Showtimes->Rooms->find('list');
        $firstRoomId = (array_keys($rooms->toArray()))[0];
        
  
        $showtimes = $this->Showtimes
            ->find()
            ->contain([
                'Movies',
                'Rooms'
            ]);
  
        if ($this->request->is('post')) {
            
            $showtimes = $showtimes->where([
                'room_id ' => $this->request->getData('room_id')
           ]);
        }    
        else{
            $showtimes = $showtimes
                ->where(['room_id ' => $firstRoomId
            ]); 
        }
        
        $showtimesByWeek = [];
        $days=["","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
        foreach($showtimes as $showtime){
            $showtimesByWeek[$showtime->start->format('N')][] = $showtime;     
        }
        
        $this->set('rooms', $rooms);
        $this->set('showtimes', $showtimes);
        $this->set('showtimesByWeek', $showtimesByWeek);
        $this->set('days', $days);
        
        $this->set('_serialize', ['room']);
    }
}
