<!DOCTYPE html>
<html lang="en">
<body>
<h4>Amrita Janani - Content Report.</h4>
<p>Hello admin.</p>
<p>We have received a report by {{$detail['name']}} about the {{$detail['filetype']}} whose uuid is {{$detail['fileid']}}. Below are the details:</p>
<p>User Name : {{$detail['name']}}</p>
<p>User Email : {{$detail['email']}}</p>
<p>File Name : {{$detail['filename']}}</p>
<p>File UUID : {{$detail['fileid']}}</p>
<p>File Type : {{$detail['filetype']}}</p>
<p>Message : {{$detail['message']}}</p>
</body>
</html>