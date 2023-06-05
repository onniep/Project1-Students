<?php

function loadManageList($filename){
    if (file_exists($filename)){
        $json = file_get_contents($filename);
        $manageStudentsList = json_decode($json, true);
        if ($manageStudentsList === null) {
            $manageStudentsList = [];
        }
    } else {
        $manageStudentsList = [];
    }
    return $manageStudentsList;
}

function saveManageStudentsList($filename, $manageStudentsList) {
    $json = json_encode($manageStudentsList);
    file_put_contents($filename, $json);
}

$filename = 'ManageStudents_list.json';

$manageStudentsList = loadManageList($filename);

if (isset($_POST['addStudent'])){
    if (!empty($_POST['registration_number']) && !empty($_POST['student_name']) && !empty($_POST['grade']) && !empty($_POST['classroom'])){
       $registrationNumber = $_POST['registration_number']; 
       $studentName = $_POST['student_name']; 
       $grade = $_POST['grade']; 
       $classroom = $_POST['classroom'];

    foreach ($manageStudentsList as $student) {
        if ($student['registration_number'] === $registrationNumber) {
        header('Location: index.php?error=DuplicateRegistrationNumber');
        exit;
    }
}
    
    $newStudent = [
        'registration_number' => $registrationNumber,
        'student_name' => $studentName,
        'grade' => $grade,
        'classroom' => $classroom,
    ];
        $manageStudentsList[] = $newStudent;
       
        saveManageStudentsList($filename, $manageStudentsList);
     }
 }

if (isset($_POST['remove'])) {
    $removeIndex = $_POST['remove'];
    if (isset($manageStudentsList[$removeIndex])) {
        unset($manageStudentsList[$removeIndex]);
        $manageStudentsList = array_values($manageStudentsList);
        saveManageStudentsList($filename, $manageStudentsList);
    }
}

if (isset($_POST['edit_index']) && isset($_POST['updated_registration_number']) &&
    isset($_POST['updated_student_name']) && isset($_POST['updated_grade']) && isset($_POST['updated_classroom'])) {
    
    $editIndex = $_POST['edit_index'];
    $updatedRegistrationNumber = $_POST['updated_registration_number'];
    $updatedStudentName = $_POST['updated_student_name'];
    $updatedGrade = $_POST['updated_grade'];
    $updatedClassroom = $_POST['updated_classroom'];

    if (isset($manageStudentsList[$editIndex])) {
        $manageStudentsList[$editIndex]['registration_number'] = $updatedRegistrationNumber;
        $manageStudentsList[$editIndex]['student_name'] = $updatedStudentName;
        $manageStudentsList[$editIndex]['grade'] = $updatedGrade;
        $manageStudentsList[$editIndex]['classroom'] = $updatedClassroom;

        saveManageStudentsList($filename, $manageStudentsList);
    }
    
}

?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Students</title>
</head>
<body>
    
    <h1>Manage Students List</h1>
    <form method="POST" action="">
        <label>Registration number:</label>
        <input type="text" id="registration_number" name="registration_number" placeholder="Enter a registration number" required>
        <br><br>
        <label>Student name:</label>
        <input type="text" id="student_name" name="student_name" required>
        <br><br>
        <label>Grade:</label>
        <select name="grade" required>
            <option value="" selected disabled>Select a grade</option>
            <?php
               for ($i = 0; $i <= 10; $i++) {
                  echo '<option value="' . $i . '">' . $i . '</option>';
                }
            ?>
        </select>
        <br><br>
        <label>Classroom:</label>
        <input type="text" id="classroom" name="classroom" required>
        <br><br>
        <button type="submit" name="addStudent">Add Student</button>
    </form>
<body>

    
    <h2>Students List</h2>

    <ul>
       <?php foreach ($manageStudentsList as $index => $student): ?>
       <li>
            <form method="POST" action="">
            <input type="hidden" name="remove" value="<?php echo $index; ?>">
            <button type="submit">Delete</button>
            </form>
            <?php echo $student['registration_number']; ?> - <?php echo $student['student_name']; ?> (Grade: <?php echo $student['grade']; ?>, Classroom: <?php echo $student['classroom']; ?>)
            <form method="POST" action="">
                <input type="hidden" name="edit_index" value="<?php echo $index; ?>">
                <label for="updated_registration_number">Update Registration Number:</label>
                <input type="text" id="updated_registration_number" name="updated_registration_number" required> <br>

                <label for="updated_student_name">Update Student Name:</label>
                <input type="text" id="updated_student_name" name="updated_student_name" required> <br>

                <label for="updated_grade">Update Grade:</label>
                <input type="text" id="updated_grade" name="updated_grade" required> <br>

                <label for="updated_classroom">Update Classroom:</label>
                <input type="text" id="updated_classroom" name="updated_classroom" required> <br>

                <button type="submit">Update</button>
            </form>
    </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>