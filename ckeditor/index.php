<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My PHP Page</title>
    <!-- <script src="https://cdn.ckeditor.com/ckeditor5/38.0.1/classic/ckeditor.js"></script> -->
    <link rel="stylesheet" href="./ckeditor5/ckeditor5.css">

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $content = $_POST['editor'] ?? '';
        $con = mysqli_connect('localhost','root','',   'editr');
        // Process the submitted content as needed
        mysqli_query($con, "INSERT INTO `editor` (`content`) VALUES ('$content')");
        echo "<h2>Submitted Content:</h2>";
        echo "<div>" . $content . "</div>";
        //header("location: index.php");
    }
    ?>
    <form method="post">

        <textarea name="editor" id="editor">
            <p>This is some sample content.</p>
        </textarea>
        <button type="submit">Submit</button>

    </form>
	   <script type="importmap">
			{
				"imports": {
					"ckeditor5": "./ckeditor5/ckeditor5.js",
					"ckeditor5/": "./ckeditor5/"
				}
			}
		</script>
	<script type="module">
			import {
				ClassicEditor,
				Essentials,
				Paragraph,
				Bold,
				Italic,
				Font
			} from 'ckeditor5';

            ClassicEditor
                .create( document.querySelector( '#editor' ), {
                    licenseKey: 'GPL', // Or <YOUR_LICENSE_KEY>
                    plugins: [ Essentials, Paragraph, Bold, Italic, Font ],
                    toolbar: [
						'undo', 'redo', '|', 'bold', 'italic', '|',
						'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
					],
					licenseKey: 'GPL'
				} )
				.then( editor => {
					window.editor = editor;
				} )
				.catch( error => {
					console.error( error );
				} );
		</script>
		<!-- A friendly reminder to run on a server, remove this during the integration. -->
		<script>
				window.onload = function() {
					if ( window.location.protocol === "file:" ) {
						alert( "This sample requires an HTTP server. Please serve this file with a web server." );
					}
				};
		</script>
</html>