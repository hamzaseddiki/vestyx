
@if($status === 'draft')
    <span class="alert alert-sm alert-warning" >{{__('Draft')}}</span>
@elseif($status === 'archive')
    <span class="alert alert-sm alert-warning" >{{__('Archive')}}</span>
@elseif($status === 'pending')
    <span class="alert alert-sm alert-warning" >{{__('Pending')}}</span>
@elseif($status == 1)
    <span class="alert alert-sm alert-success" >{{__('Active')}}</span>
@elseif($status == 0)
    <span class="alert alert-sm alert-danger" >{{__('In Active')}}</span>
@elseif($status === 'active')
    <span class="alert alert-sm alert-success" >{{__('Active')}}</span>
@elseif($status === 'inactive')
    <span class="alert alert-sm alert-danger" >{{__('Inactive')}}</span>
@elseif($status === 'complete')
    <span class="alert alert-sm alert-success" >{{__('Complete')}}</span>
@elseif($status === 'close')
    <span class="alert alert-sm alert-danger" >{{__('Close')}}</span>
@elseif($status === 'in_progress')
    <span class="alert alert-sm alert-info" >{{__('In Progress')}}</span>
@elseif($status === 'publish')
    <span class="alert alert-sm alert-success" >{{__('Publish')}}</span>
@elseif($status === 'approved')
    <span class="alert alert-sm alert-success" >{{__('Approved')}}</span>
@elseif($status === 'confirm')
    <span class="alert alert-sm alert-success" >{{__('Confirm')}}</span>
@elseif($status === 'yes')
    <span class="alert alert-sm alert-success" >{{__('Yes')}}</span>
@elseif($status === 'no')
    <span class="alert alert-sm alert-danger" >{{__('No')}}</span>
@elseif($status === 'cancel')
    <span class="alert alert-sm alert-danger" >{{__('Cancel')}}</span>
@endif
