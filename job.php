<?php
 
    

    use App\Models\{Job, Project};

    $jobs = Job::all();

      $project1 = new project('Project 1', 'Description 1');
      $projects = [
        $project1
      ];
    
      function printElement( $jobs) {
        //if($jobs->visible == false) {
          //return;
        //}
        echo '<li class="work-position">';
        echo '<h5>' . $jobs->title . '</h5>';
        echo '<h5>' . $jobs->description . '</h5>';
        echo '<h5>' . $jobs->getDurationasString() . '</h5>';
        echo '<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi sapiente sed pariatur sint exercitationem eos expedita eveniet veniam ullam, quia neque facilis dicta voluptatibus. Eveniet doloremque ipsum itaque obcaecati nihil.</p>';
        echo '<strong>Achievements:</strong>';
        echo '<ul>';
        echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
        echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
        echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
        echo '</ul>';
        echo '</li>';
      }
    
