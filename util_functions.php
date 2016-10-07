<?php

	function sanitizeString($str) {
		$str = stripslashes($str);
		$str = strip_tags($str);
		$str = htmlentities($str);
		return $str;
	}

	function sanitizeMySQL($conn, $str) {
		$str = $conn->real_escape_string($str);
		$str = sanitizeString($str);
		return $str;
	}

	
	function validateUser($conn, $maybe_un, $maybe_pw) {
		$valid = false;

		$query = "SELECT * FROM users WHERE username = '$maybe_un'";
		$result = $conn->query($query);

		if (!$result) {
			echo ("No such user found.  Maybe make a new user?");
		} else if ($result->num_rows) {
			$row = $result->fetch_array(MYSQLI_NUM);
			$token = tokenize($maybe_pw);
			if ($token == $row[2]) {
				session_start();
				$_SESSION['id_user'] = $row[0];
				$_SESSION['username'] = $maybe_un;
				$_SESSION['userLast'] = $row[3];
				$_SESSION['userFirst'] = $row[4];
				echo "Success";
				$valid = true;
			} else {
				echo "Invalid password";
			}
		} else {
			echo ("User not found");
		}
		return $valid;
	}
	
	function isOwnerSection($conn, $id_user, $id_section) {
		$query = "SELECT * FROM permissions WHERE id_user='$id_user' AND id_section='$id_section'";
		$results = $conn->query($query);
		$toRet = ($results->num_rows > 0);
		return $toRet;
	}
	
	function isOwnerStudent($conn, $id_user, $id_student) {
		$query = "SELECT * FROM permissions JOIN enrollments ON permissions.id_section = enrollments.id_section WHERE permissions.id_user='$id_user' AND enrollments.id_student='$id_student'";
		$results = $conn->query($query);
		$toRet = ($results->num_rows > 0);
		return $toRet;
	}
	
	function isOwnerEnrollment($conn, $id_user, $id_enrollment) {
		$query = "SELECT * FROM permissions JOIN enrollments ON permissions.id_section = enrollments.id_section WHERE permissions.id_user='$id_user' AND enrollments.id_enrollment='$id_enrollment'";
		$results = $conn->query($query);
		$toRet = ($results->num_rows > 0);
		return $toRet;
	}

	function isUserExists($conn, $username) {
		$query = "SELECT * FROM users WHERE username='$username'";
		$result = $conn->query($query);
		$num = $result->num_rows;
		return ($num > 0);
	}
	
	
	function getRandomEnrollment($conn, $id_section) {
		$query = "SELECT * FROM enrollments JOIN students ON enrollments.id_student = students.id_student WHERE enrollments.id_section = '$id_section'";
		$result = $conn->query($query);
		if (!$result) die("Database access failed: " . $conn->error);

		$rows = $result->num_rows;

		if ($rows == 0) {
			return 0;
		} else {
			$rand = rand(0, $rows - 1);
			$result->data_seek($rand);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			return $row['id_enrollment'];
		}
	}
	
	function getScoresArray($conn, $id_enrollment) {
		$query_score = "SELECT score, count(*) AS freq FROM marks WHERE id_enrollment = '$id_enrollment' GROUP BY score";
		$result = $conn->query($query_score);
		if (!$result) die("Score retrieval failed: " . $conn->error);

		$scores = array(0, 0, 0, 0, 0, 0);
		$numScores = $result->num_rows;
		for ($j = 0 ; $j < $numScores; $j++) {
			$result->data_seek($j);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$scores[$row['score']] = $row['freq'];
		}
		
		return $scores;
	}

	function getSection($conn, $id_enrollment) {
		$query = "SELECT id_section FROM enrollments WHERE id_enrollment='$id_enrollment'";
		$result = $conn->query($query);
		if (!$result) die ("no section found for that enrollment");
		$row = $result->fetch_array(MYSQLI_NUM);
		return $row[0];
	}
	
	function getStudentsInSection($conn, $id_section) {
		$studentCountQuery = "SELECT count(*) FROM enrollments WHERE id_section='$id_section'";
		$studentCountResult = $conn->query($studentCountQuery);
		if (!$studentCountResult) die("Database access failed: " . $conn->error);
		$countRow = $studentCountResult->fetch_array(MYSQLI_NUM);
		$count = $countRow[0];
		return $count;
	}
	
	function getStudentField($conn, $id_student, $field) {
		if ($id_student == 0) {
			return;
		} else {
			$query = "SELECT $field FROM students WHERE id_student='$id_student'";
			$result = $conn->query($query);
			if (!$result) die ("couldn't lookup $field for student");
			$student = $result->fetch_array(MYSQLI_NUM);
			return $student[0];
		}
	}
	
	function getSectionField($conn, $id_section, $field) {
		if ($id_section == 0) {
			return;
		} else {
			$query = "SELECT $field FROM sections WHERE id_section='$id_section'";
			$result=$conn->query($query);
			if (!$result) die ("couldn't lookup $field for section");
			$section = $result->fetch_array(MYSQLI_NUM);
			return $section[0];
		}
	}
	

	function echoStudentTable($conn, $id_section) {
		$query = "SELECT * FROM enrollments JOIN students ON enrollments.id_student = students.id_student WHERE enrollments.id_section = '$id_section' ORDER BY students.last";
		$result = $conn->query($query);
		if (!$result) die("Database access failed: " . $conn->error);
		
		$rows = $result->num_rows;
		
		if ($rows == 0) {
			echo "[no students yet!]";
		} else {
			echo "<table>";
			//echo "<colgroup><col width='4*'><col width='1*'><col width='1*'><col width='1*'>";
			//echo "<col width='1*'><col width='1*'><col width='1*'><col width='2*'></colgroup>";
			echo "<thead>";
			echo "<tr><th>Name</th><th>+</th><th>A</th><th>B</th><th>C</th><th>D</th><th>F</th><th>-</th></tr>";
			echo "</thead><tbody>";
			for ($i = 0 ; $i < $rows ; $i++) {
				$result->data_seek($i);
				$row = $result->fetch_array(MYSQLI_ASSOC);
				echo "<tr>";
				echo "<td><a href='#' class='callStudent button name' data-id_enrollment='{$row['id_enrollment']}'>{$row['first']} {$row['last']}</a></td>";

				$id_enrollment = $row['id_enrollment'];
				$scores = getScoresArray($conn, $id_enrollment);
				for ($j = count($scores) - 1 ; $j >= 0 ; $j--) {
					if ($scores[$j] == 0) {
						echo "<td>-</td>";
					} else {
						echo "<td>$scores[$j]</td>";
					}
				}
				echo "<td>";
				echo "<a href='#' class='button editStudent details' data-id_student='{$row['id_student']}' data-id_section='$id_section'>Edit</a>";
				echo "</td>";
				echo "</tr>";
			}
			echo "</tbody></table>";
		}
	}
	
	function echoSectionTable($conn, $id_user) {
		$query = "SELECT * FROM permissions JOIN sections ON permissions.id_section = sections.id_section WHERE permissions.id_user = '$id_user' ORDER BY sections.period";
		$result = $conn->query($query);
		if (!$result) die("Database access failed: " . $conn->error);
		
		$rows = $result->num_rows;
		if ($rows == 0) {
			echo "[no classes yet!]";
		} else {
			echo "<table>";
			//echo "<colgroup><col width='10%'><col width='20%'><col width='10%'><col width='60%'></colgroup>";
			echo "<thead><tr><th>Period</th><th>Title</th><th>Students</th><th></th></tr></thead><tbody>";
			for ($i = 0 ; $i < $rows ; $i++) {
				$result->data_seek($i);
				$row = $result->fetch_array(MYSQLI_ASSOC);
				$id_section = $row['id_section'];
				$count = getStudentsInSection($conn, $id_section);
				echo "<tr>";
				echo "<td>{$row['period']}</td>";
				echo "<td><a href='#' class='button viewSection name' data-id_section='$id_section'>{$row['title']}</a></td>";
				echo "<td>$count</td><td>";
				echo "<a href='#' class='button editSection details' data-id_section='$id_section'>Edit</a>";
				echo "</td></tr>";
			}
			echo "</tbody></table>";
		}
	}

	function echoEnrollmentHeading($conn, $id_enrollment) {
		$query = "SELECT st.first, st.last, se.abbrev FROM students AS st JOIN enrollments as en ON en.id_student = st.id_student JOIN sections AS se ON se.id_section = en.id_section WHERE en.id_enrollment = '$id_enrollment'"; 
		
		$result = $conn->query($query);
		if (!$result) die("Database access failed: " . $conn->error);
		
		$rows = $result->num_rows;
		for ($i = 0 ; $i < $rows ; $i++) {
			$result->data_seek($i);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			echo "{$row['first']} {$row['last']} in {$row['abbrev']}";
		}
	}

	function echoSectionHeading($conn, $id_section) {
		$query = "SELECT * FROM sections WHERE id_section = '$id_section'";
		$result = $conn->query($query);
		if (!$result) die("Database access failed: " . $conn->error);
		
		$rows = $result->num_rows;
		
		for ($i = 0 ; $i < $rows ; $i++) {
			$result->data_seek($i);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			echo "Period {$row['period']}: {$row['title']}";
		}
	}
	
	function echoSectionScores($conn, $id_section) {
		$query = "SELECT * FROM enrollments JOIN students ON enrollments.id_student = students.id_student WHERE enrollments.id_section = '$id_section' ORDER BY students.last";
		$result = $conn->query($query);
		if (!$result) die("Database access failed: " . $conn->error);
		
		$rows = $result->num_rows;
		
		if ($rows == 0) {
			echo "[no students yet!]";
		} else {
			echo "<table>";
			echo "<tr><td>Last</td><td>First</td><td>+</td><td>A</td><td>B</td><td>C</td><td>D</td><td>F</td></tr>";
			for ($i = 0 ; $i < $rows ; $i++) {
				$result->data_seek($i);
				$row = $result->fetch_array(MYSQLI_ASSOC);
				echo "<tr><td>{$row['last']}</td><td>{$row['first']}</td>";

				$id_enrollment = $row['id_enrollment'];
				$scores = getScoresArray($conn, $id_enrollment);
				for ($j = count($scores) - 1 ; $j >= 0 ; $j--) {
					echo "<td>$scores[$j]</td>";
				}
				echo "</tr>";
			}
			echo "</table>";
		}
	}
	
	
	function insertUser($conn, $first, $last, $email, $username, $token) {
		if (!isUserExists($conn, $username)) {
			$query = "INSERT INTO users(username, last, first, token, email) VALUES('$username', '$last', '$first', '$token', '$email')";
			$result = $conn->query($query);
			if ($result) {
				echo "Added $first $last to table of users<br>";
				echo "<a href='util_login.php' class='button'>CLICK HERE TO LOGIN</a>";
			} else {
				echo $conn->error;
			}
		} else {
			echo "Username $username already taken!";
		}
	}
	
	function insertStudent($conn, $first, $last, $id_section) {
		$query = "INSERT INTO students(last, first) VALUES('$last', '$first')";
		$result = $conn->query($query);
		
		if ($result) {
			$id_student = $conn->insert_id;
			$query = "INSERT INTO enrollments(id_section, id_student) VALUES('$id_section', '$id_student')";
			$result = $conn->query($query);
			if ($result) {
				echo "added $first $last to students";
			} else {
				echo "enrollment failure; will not show up in class";
			}
		} else {
			echo "could not add $first $last to students";
		}
	}
	
	function updateStudent($conn, $id_student, $first, $last) {
		$query = "UPDATE students SET first='$first', last='$last' WHERE id_student='$id_student'";
		$result = $conn->query($query);
		
		if ($result) {
			echo "updated $first $last";
		} else {
			echo "couldn't update $first $last";
		}
	}

	function deleteAllScores($conn, $id_section) {
		$query = "SELECT id_enrollment FROM enrollments WHERE id_section='$id_section'";
		$result = $conn->query($query);
		$rows = $result->num_rows;
		for ($i = 0 ; $i < $rows ; $i++) {
			$result->data_seek($i);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$id_enrollment = $row['id_enrollment'];
			deleteEnrollmentScores($conn, $id_enrollment);
		}
	}
	
	function deleteScores($conn, $id_student, $id_section) {
		$query = "SELECT id_enrollment FROM enrollments WHERE id_section='$id_section' AND id_student='$id_student'";
		$result = $conn->query($query);
		$rows = $result->num_rows;
		if ($rows > 0) {
			$result->data_seek(0);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$id_enrollment = $row['id_enrollment'];
			deleteEnrollmentScores($conn, $id_enrollment);
		} else {
			echo "No enrollment found for that student.  This should not happen";
		}
	}
	
	function deleteEnrollmentScores($conn, $id_enrollment) {
		$delQuery = "DELETE FROM marks WHERE id_enrollment='$id_enrollment'";
		$result = $conn->query($delQuery);
		if ($result) {
			echo "deleted scores for student";
		} else {
			echo "couldn't delete scores";
		}
	}
	
	function deleteStudent($conn, $id_student) {
		$query = "DELETE FROM students WHERE id_student='$id_student'";
		$result = $conn->query($query);
		
		if ($result) {
			echo "deleted student $id_student";
		} else {
			echo "couldn't delete student $id_student";
		}

		$query = "DELETE FROM enrollments WHERE id_student='$id_student'";
		$result = $conn->query($query);
	}
	
	function insertSection($conn, $id_user, $title, $abbrev, $period, $subject) {
		$query = "INSERT INTO sections(title, abbrev, period, subject) VALUES('$title', '$abbrev', '$period', '$subject')";
		$result = $conn->query($query);
	
		if ($result) {
			$id_section = $conn->insert_id;
			$query = "INSERT INTO permissions(id_user, id_section, owner) VALUES('$id_user', '$id_section', TRUE)";
			$result = $conn->query($query);
			if ($result) {
				echo "added $title to table of sections";
			} else {
				echo "permissions link failure";
			}
		}
	}

	function updateSection($conn, $id_section, $title, $abbrev, $period, $subject) {
		$query = "UPDATE sections SET title='$title', abbrev='$abbrev', period='$period', subject='$subject' WHERE id_section='$id_section'";
		$result = $conn->query($query);
	
		if ($result) {
			echo "updated $title";
		} else {
			echo "couldn't update $title";
		}
	}

	function deleteSection($conn, $id_section) {
		$query = "DELETE FROM sections WHERE id_section='$id_section'";
		$result = $conn->query($query);
		if ($result) {
			echo "deleted section";
		} else {
			echo "couldn't delete section";
		}
	
		
		// deleting students if their class is deleted
		// change this if same student in multiple classes!  
		$query = "SELECT id_student FROM enrollments WHERE id_section='$id_section'";
		$result = $conn->query($query);
		$rows = $result->num_rows;
		for ($i = 0 ; $i < $rows ; $i++) {
			$result->data_seek($i);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$id_student = $row['id_student'];
			$delQuery = "DELETE FROM students WHERE id_student='$id_student'";
			echo $delQuery;
			$conn->query($delQuery);
		}
		
		$query = "DELETE FROM enrollments WHERE id_section='$id_section'";
		$result = $conn->query($query);
	}

	function insertMark($conn, $id_enrollment, $score) {
		$query = "INSERT INTO marks(id_enrollment, score) VALUES('$id_enrollment', '$score')";
		$result = $conn->query($query);
		
		if ($result) {
			echo "$score given";
		} else {
			echo "could not add mark";
		}
	}
?>