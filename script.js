function azioni(tipoazione, idpost) {
  console.log("ciao");
  console.log(idpost);
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log(this.response);
      console.log(idpost);
      
      let post = document.getElementById(idpost);
      console.log(post);
      
      console.log("#" + idpost + " .btns .btnSave");
      
      let divbtns = document.querySelector("#" + idpost + " .btns");
      
      let btn = null;
      let spannlikes = null;
      
      if (tipoazione == "like") {
        btn = document.querySelector("#" + idpost + " .btns .btnSave");
      } else {
        spannlikes = document.querySelector("#" + idpost + " .btns .nlikes");
        btn = document.querySelector("#" + idpost + " .btns .btnlike");
      }
      
      console.log(btn);
      
      if (tipoazione == "like") {
        console.log(btn);
        
        divbtns.innerHTML = this.responseText;
        divbtns.appendChild(btn);
      }else{
   

        while (divbtns.firstChild) {
          divbtns.removeChild(divbtns.lastChild);
        }

        divbtns.appendChild(btn);
        divbtns.appendChild(spannlikes);
        //divbtns.innerHTML = btnlike;
        //divbtns.innerHTML += likes;
        divbtns.innerHTML += this.response;
        
      }

    }
  };
  xmlhttp.open("GET", "azioni.php?" + tipoazione + "=" + idpost);
  xmlhttp.send();
}


function view(post){
  console.log(post);
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log(this.response);

      let divbody = document.querySelector(".sec-view .view-body");
      
      document.querySelector(".sec-view .sec-labels button").style.border = "none";
      
      switch(post){
        case "post-pubb":
          document.querySelector(".sec-view .sec-labels button").style.border = "none";
          document.querySelector(".sec-view .sec-labels button").style.borderBottom = "none";
          document.getElementById("btn-post-pubb").style.borderBottom = "black 1px solid";
          break;
        case "post-liked":
          document.querySelector(".sec-view .sec-labels button").style.borderBottom = "none";
          document.querySelector(".sec-view .sec-labels button").style.border = "none";
          document.getElementById("btn-post-liked").style.borderBottom = "black 1px solid";
          break;
        case "post-saved":
          document.querySelector(".sec-view .sec-labels button").style.borderBottom = "none";
          document.getElementById("btn-post-saved").style.borderBottom = "black 1px solid";
          break;
      }

      divbody.innerHTML = this.response;

    }
  };
  xmlhttp.open("GET", "azioni.php?view-post=" + post);
  xmlhttp.send();
}

let path = window.location.pathname;

console.log(path);


if(path=="/social_yassine/profile.php"){
  window.onload = view("post-pubb");
}

function showmsg(str){
  alert(str);
}