(function(){
  let comment = document.getElementById('js-comment');
  let comment_time = document.getElementById('js-comment-time');
  let comment_date = document.getElementById('js-comment-date');
  let comment_num = document.getElementById('js-comment-num');

  let commentTarget = document.getElementById('js-comm-target');
  let commentTimeTarget = document.getElementById('js-commT-target');
  let commentDateTarget = document.getElementById('js-commD-target');
  let commentNumTarget = document.getElementById('js-commN-target');

  let commentStyle = window.getComputedStyle(comment);
  let commentTStyle = window.getComputedStyle(comment_time);
  let commentDStyle = window.getComputedStyle(comment_date);
  let commentNStyle = window.getComputedStyle(comment_num);

  commentTarget.style.width = commentStyle.width;
  commentTimeTarget.style.width = commentTStyle.width;
  commentDateTarget.style.width = commentDStyle.width;
  commentNumTarget.style.width = commentNStyle.width;

  comment.addEventListener('click', function(){
    comment.addEventListener('mousemove',function(){
      commentTarget.style.width = commentStyle.width;
      console.log(commentTarget.style.width);
      console.log(commentStyle.width);
    });
  });
  comment_time.addEventListener('click', function(){
    comment_time.addEventListener('mousemove',function(){
      commentTimeTarget.style.width = commentTStyle.width;
      console.log(commentTimeTarget.style.width);
    });
  });
  comment_date.addEventListener('click', function(){
    comment_time.addEventListener('mousemove',function(){
      commentTimeTarget.style.width = commentTStyle.width;
      console.log(commentTimeTarget.style.width);
    });
  });
  comment_num.addEventListener('click', function(){
    comment_time.addEventListener('mousemove',function(){
      commentTimeTarget.style.width = commentTStyle.width;
      console.log(commentTimeTarget.style.width);
    });
  });
})()
