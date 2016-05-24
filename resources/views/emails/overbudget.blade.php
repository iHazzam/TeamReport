<p>
  Hi, <br>
  This is an email notification to inform you that project {{$details['projectname']}} has gone over budget.
</p>
<p>
  Here are the details:
</p>
<ul>
  <li>Project: <strong>{{$details['projectname']}}</strong></li>
  <li>Budget: <strong>{{ $details['budget'] }}</strong></li>
  <li>Used: <strong>{{ $details['spend'] }}</strong></li>
  <li>Date: <strong>{{ date("d/m/Y") }}</strong></li>
</ul>
<p>
  Note: This email is sent by a bot. Please do not reply to this email.
  See an error? Contact Developers@nublue.co.uk
</p>
