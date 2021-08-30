<?php require_once "autoload.php" ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
<div class="container p-2 m-3 ">
    <div>
        <h3 class="text-primary">To do List</h3>
        <p class="border-bottom border-info "></p>
    </div>
    <form class="container d-flex justify-content-between p-2" action="add.php" method="POST">
        <div class="">
            <?php if (isset($_SESSION['message'])) {
                echo "<div class='alert alert-warning py-2 rounded-2' role='alert'>" . $_SESSION['message'] . "</div>";
                unset($_SESSION['message']);
            }
            ?>
            <input class="form-control" type="text" id="task" name="task" placeholder="Enter your task">
            <button type="submit" name = "add" class="btn btn-sm btn-primary mt-2">Add</button>
        </div>
        <div class="">
            <input name="due" type="date" class="btn btn-sm btn-primary" id="due" value="">Due Date
        </div>
    </form>
    <div class="p-1 container text-white m-2">
        <div class="col container d-block">
            <?php
            $data = DB::table('tasks')->orderBy('due_date','DESC')->paginate(2);

            foreach ($data['data'] as $task) :?>
                {
                <div class='row-2 container tasks d-flex'>

                    <div class="col-md-8 col-sm-8 col-6">
                        <input data-id="<?php echo $task->id ?>" class="task-check form-check-input me-2 "
                               type="checkbox" value="" <?php echo $task->completion ? 'checked' : '' ?> />
                            <span class="h5 badge bg-warning text-dark name p-2 <?php echo $task->completion? 'text-decoration-line-through':''?>"><?php echo $task->name ?></span>
                    </div>
                    <div class="col-md-2 col-4 col-sm-6">
                        <p class='badge bg-success text-dark'>
                            <?php
                            $timeStamp = $task->due_date;
                            if (is_null($timeStamp)) {
                                echo "No due date";
                            } else {
                                $timeStamp = date("m/d", strtotime($timeStamp));
                                echo "Due date: $timeStamp";
                            } ?>
                        </p>
                    </div>
                    <div class="col-md-2 col-2">
                        <a href="delete.php?id=<?php echo $task->id ?>" class="delete" >
                            <i class='btn btn-sm bi bi-x-circle ' style='color:darkblue;'></i>
                        </a>
                    </div>
                </div>
                }
            <?php endforeach; ?>
        </div>
    </div>
    <ul class="pagination justify-content-end">
        <?php
        $next_page = $data['next_page'];
        $prev_page = $data['prev_page'];

        if ($data['current_page_no'] != $data['total_pages'])
        {
            echo "<li class='page-item'>
                    <a class='page-link' href=$next_page>Next Page</a>
                    </li>";
        }
        if ($data['current_page_no'] !=1)
        {
            echo "<li>
            <a class='page-link' href=$prev_page>Previous Page</a>
                </li>";
        }
        ?>

    </ul>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script>
    $('.task-check').on('click', function() {
        checkAction = $(this).is(":checked");
        $(this).prop("disabled", true);
        $.ajax({
            url: './api/completion.php',
            type: 'POST',
            data: JSON.stringify({
                id: $(this).data('id'),
                completion: checkAction
            }),
            success: (res) => {
                var toggle = $(this).next().toggleClass('text-decoration-line-through');
                $(this).prop("disabled", false);
            }
        })
    })
</script>
</body>

</html>

