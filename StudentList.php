<?php
include "db_conn.php";

if(isset($_POST['btnAdd'])) {
    $Rollno = $_POST['Rollno'];
    $Sname = $_POST['Sname'];
    $Address = $_POST['Address'];
    $Email = $_POST['Email'];

    if($Rollno=="" || $Sname=="" || $Address=="" || $Email=="") {
        echo "(*) is not empty";
    } else {
        if($_POST['btnAdd'] == "Add") {
            $sql = "SELECT Rollno FROM students WHERE Rollno='$Rollno'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result)==0) {
                $sql = "INSERT INTO students VALUES ('$Rollno', '$Sname', '$Address', '$Email')";
                mysqli_query($conn, $sql);
                header("Location: StudentList.php");
                exit();
            } else {
                echo "Existed student in list";
            }
        } elseif ($_POST['btnAdd'] == "Update") {
            $sql = "UPDATE students SET Sname='$Sname', Address='$Address', Email='$Email' WHERE Rollno='$Rollno'";
            mysqli_query($conn, $sql);
            header("Location: StudentList.php");
            exit();
        }
    }
}

if(isset($_GET['delete'])) {
    $rollnoToDelete = $_GET['delete'];
    $sql = "DELETE FROM students WHERE Rollno='$rollnoToDelete'";
    mysqli_query($conn, $sql);
    header("Location: StudentList.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <style>
     body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

table caption {
    font-size: 1.5em;
    margin-bottom: 10px;
    color: #000; /* Màu đen */
}

th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    color: #000; /* Màu đen */
}

th {
    background-color: #000; /* Màu đen */
    color: white;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #ddd;
}

form {
    width: 80%;
    margin: 20px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

form caption {
    font-size: 1.5em;
    margin-bottom: 10px;
    text-align: center;
    color: #000; /* Màu đen */
}

input[type="text"], input[type="submit"], input[type="reset"], .editBtn, .deleteBtn {
    padding: 10px 20px;
    margin-bottom: 10px;
    width: calc(30% - 22px); /* Adjusted width for three buttons */
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
    margin-right: 2%;
}

input[type="submit"], input[type="reset"] {
    background-color: #FF69B4; /* Màu xanh cho nút Add và Cancel */
    color: white;
}

input[type="submit"]:hover, input[type="reset"]:hover {
    background-color: #45a049; /* Màu xanh tương tác */
}

.editBtn {
    background-color: #4CAF50; /* Màu xanh cho nút Edit */
    color: white;
}

.deleteBtn {
    background-color: #f44336; /* Màu đỏ cho nút Delete */
    color: white;
}

.editBtn:hover, .deleteBtn:hover {
    background-color: #45a049; /* Màu xanh tương tác */
}

 </style>
</head>
<body>
    <?php
    include "db_conn.php";
    $sql = "SELECT * FROM students";
    $result = mysqli_query($conn, $sql);
    ?>

    <table align="center" border="1px" cellpadding="0" cellspacing="0">
        <caption align="center">Student List</caption>
        <tr>
            <th>Rollno</th>
            <th>Student Fullname</th>
            <th>Address</th>
            <th>Email</th>
            <th>Action</th>
        </tr>

        <?php
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        ?>
            <tr>
                <td><?php echo $row['Rollno']; ?></td>
                <td class="edit"><?php echo $row['Sname']; ?></td>
                <td class="edit"><?php echo $row['Address']; ?></td>
                <td class="edit"><?php echo $row['Email']; ?></td>
                <td>
        <button class="editBtn" data-id="<?php echo $row['Rollno']; ?>">Edit</button>
        <a href="StudentList.php?delete=<?php echo $row['Rollno']; ?>" onclick="return confirm('Are you sure you want to delete this student?')" class="deleteBtn">Delete</a>
              </td>
            </tr>
        <?php
        }
        ?>

    </table>

    <form method="post" id="AddStudent">
        <table align="center" border="0" cellpadding="1" cellspacing="1">
            <caption align="center"><b>Adding Student</b></caption>
            <tr>
                <td>Rollno</td>
                <td><input type="text" name="Rollno"/>(*)</td>
            </tr>

            <tr>
                <td>Student Name</td>
                <td><input type="text" name="Sname"/>(*)</td>
            </tr>

            <tr>
                <td>Student Address</td>
                <td><input type="text" name="Address"/>(*)</td>
            </tr>

            <tr>
                <td>Student Email</td>
                <td><input type="text" name="Email"/>(*)</td>
            </tr>

            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="Add" name="btnAdd"/>
                    <input type="reset" value="cancel" name="btnCancel"/>
                </td>
            </tr>
        </table>
    </form>

    <!-- Script for handling edit functionality -->
    <script>
        document.querySelectorAll('.editBtn').forEach(item => {
            item.addEventListener('click', event => {
                const rollno = item.getAttribute('data-id');
                const cells = item.parentElement.parentElement.querySelectorAll('.edit');
                const data = Array.from(cells).map(cell => cell.textContent.trim());

                document.querySelector('input[name="Rollno"]').value = rollno;
                document.querySelector('input[name="Sname"]').value = data[0];
                document.querySelector('input[name="Address"]').value = data[1];
                document.querySelector('input[name="Email"]').value = data[2];

                document.querySelector('input[name="btnAdd"]').value = "Update";

                document.getElementById('AddStudent').addEventListener('submit', function (event) {
                    event.preventDefault();
                    const rollno = document.querySelector('input[name="Rollno"]').value;
                    const sname = document.querySelector('input[name="Sname"]').value;
                    const address = document.querySelector('input[name="Address"]').value;
                    const email = document.querySelector('input[name="Email"]').value;

                    const formData = new FormData();
                    formData.append('Rollno', rollno);
                    formData.append('Sname', sname);
                    formData.append('Address', address);
                    formData.append('Email', email);
                    formData.append('btnAdd', 'Update');

                    fetch('StudentList.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (response.ok) {
                            location.reload();
                        } else {
                            console.error('Update failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });
        });
    </script>
</body>
</html>
