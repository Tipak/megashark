<?php 

    echo $this->Form->create();
    echo $this->Form->control('room_id', ['options' => $rooms]);
    echo $this->Form->button(__('Submit'));
    echo $this->Form->end(); 
    ?>
        <table>
        <tbody>
            <tr>
                <?php for($i=1; $i<8; $i++){ ?>
                    <td>
                        <?php echo $days[$i];  
                         if(isset($showtimesByWeek[$i])){?>
                           
                           <ul>
                                <?php foreach ($showtimesByWeek[$i] as $showtime): ?>
                                              
                                    <li><?php echo $showtime->movie->name ?></li>
                                    <li><?php echo $showtime->start->format("H:i") ?></li>
                                    <li><?php echo $showtime->end->format("H:i") ?></li>
                            
                                <?php endforeach; ?>
                            </ul>
                            
                        <?php } ?>
                    </td>
                <?php } ?>
            </tr>
      </tbody>

        
    </table>
