                                
                       


                                var createdAt{{$tr->id}} = document.getElementById('created_at_{{$tr->id}}').innerHTML;
                                var endTimeString{{$tr->id}} = createdAt{{$tr->id}};
                                var endTimeDate{{$tr->id}} = new Date(endTimeString{{$tr->id}});
                                var gmt8 = { timeZone: 'Asia/Singapore', hour12: false };
                                var endTimeStringFormatted{{$tr->id}} = endTimeDate{{$tr->id}}.toLocaleString('en-US', gmt8);
                                var endTime{{$tr->id}} = new Date(endTimeStringFormatted{{$tr->id}});
                                endTime{{$tr->id}}.setTime(endTime{{$tr->id}}.getTime() + 1 * 20 * 1000);

                                document.getElementById('createdAt_{{$tr->id}}').innerHTML = createdAt{{$tr->id}}
                                document.getElementById('expireTime_{{$tr->id}}').innerHTML = createdAt{{$tr->id}}
                                                  // Set the date we're counting down to
                                var countdownDate{{$tr->id}} = endTime{{$tr->id}};
                                        // Update the countdown every 1 second
                                     var x = setInterval(function() {
                                            // Get today's date and time
                                            var now = new Date().getTime();

                                            // Find the distance between now and the countdown date
                                            var distance{{$tr->id}} = countdownDate{{$tr->id}} - now;

                                            // Time calculations for days, hours, minutes, and seconds
                                            var hours = Math.floor((distance{{$tr->id}} % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                            var minutes = Math.floor((distance{{$tr->id}} % (1000 * 60 * 60)) / (1000 * 60));
                                            var seconds = Math.floor((distance{{$tr->id}} % (1000 * 60)) / 1000);

                                            // Display the result in the element with id="countdown"
                                            var countdowns = document.querySelectorAll("#countdown_{{$tr->id}}");
                                            countdowns.forEach(function(countdown){
                                                countdown.innerHTML = ("0" + hours).slice(-2) + ":" + ("0" + minutes).slice(-2) + ":" + ("0" + seconds).slice(-2);
                                            });

                                            




                                            // If the countdown is over, write some text 
                                            if (distance{{$tr->id}} < 0) {
                                                
                                                var formsCancel_{{$tr->id}} = document.querySelectorAll('#formCancel_{{$tr->id}}');
                                                formsCancel_{{$tr->id}}.forEach(function(f){
                                                    f.classList.add('d-none');
                                                });
    
                                               
                                                
                                                var paymentStatusPending_{{$tr->id}} = document.querySelectorAll("#paymentStatusPending_{{$tr->id}}");
                                                paymentStatusPending_{{$tr->id}}.forEach(function(p){
                                                    p.innerHTML = 'Transaksi Dibatalkan';
                                                    p.classList.add('text-danger');
                                                    p.classList.remove('text-warning');
                                                    
                                                    clearInterval(x);
                                                });
                                                var paybuttons{{$tr->id}} = document.querySelectorAll('#pay-button{{$tr->id}}');
                                                paybuttons{{$tr->id}}.forEach(function(pbtn){
                                                    pbtn.classList.add('d-none');
                                                });
                                                countdowns.forEach(function(countdown){
                                                countdown.innerHTML = '';
                                            });
                                            }
                                        }, 1000);