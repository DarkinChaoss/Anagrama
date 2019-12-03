function FileFrame(fileArea, fileTitle) {
  var self = this;

  this.fileArea = fileArea;
  this.fileTitle = fileTitle;

  this.init = function() {
    // Registrando eventos de drag and drop
    self.fileArea.addEventListener("dragleave", self.dragHover, false);
    self.fileArea.addEventListener("dragover", self.dragHover, false);
    self.fileArea.addEventListener("drop", self.drop, false);
 
  };

  this.dragHover = function(e) {
    // Impede poss�veis tratamentos dos arquivos
    // arrastados pelo navegador, por exemplo, exibir
    // o conteudo do mesmo.
    e.stopPropagation();  
    e.preventDefault();  

    // Quando o arquivo est� sobre �rea alteramos o seu estilo
    self.fileArea.className = (e.type == "dragover" ? "hover" : "");  
  };

  this.drop = function(e) {
    self.dragHover(e);  

    // Volta um array com os arquivos arratados,
    // por�m neste exemplo iremos tratar apenas
    // o primeiro arquivo
    self.file = e.dataTransfer.files[0];  
   
    // Recupera nome do arquivo
    self.fileTitle.innerHTML = self.file.name;

    self.read(self.file);
    
    // Neste ponto podemos implementar uma fun��o para
    // enviar os arquivos via ajax.
    // Irei deixar um exemplo, qualquer d�vida eu pe�o
    // que utilize o sistema de coment�rios do site.
    
    self.sendFile(self.file);
    
  };

  // Esse m�todo ir� ler o arquivo na mem�ria,
  // depois iremos mostr�-lo no nosso frame
  this.read = function(file) {
    // Iremos ler apenas imagens nesse exemplo
    // e iremos exibi-lo no frame
    if (file.type.match('image.*')) {
      var reader = new FileReader();

      // Callback que ser� executado ap�s a leitura do arquivo
      reader.onload = function(f) {
        self.fileArea.innerHTML = "";
        self.fileArea.setAttribute("style", "padding: 0px !important;");
        
        // Cria��o do elemento que ser� utilizado para exibir a imagem
        var img = document.createElement("img");
        img.setAttribute("src", f.target.result);
        img.setAttribute("height", "350");

        self.fileArea.appendChild(img);
        //alert(f.target.result);
        //location.href = f.target.result;
        $.post("upload", {imagem:f.target.result}, function(data){
        	alert(data);
        });
      }

      // Ir� ler o arquivo para ser acessado atrav�s de uma url
      reader.readAsDataURL(file);
      //alert(file.name);
    }
  }

  // Essa fun��o pode ser utilizada como 
  this.sendFile = function(file) {

    // Criaremos um formul�rio
    var f = new FormData();
    // Passando o arquivo para o formul�rio
    f.append("file", file);

    // Chamada async para realizar o upload da imagem
    var request = new XMLHttpRequest();
    request.open("POST", "", true);
    request.send(f);
    request.onreadystatechange=function(){
      // T�rmino do envio do formul�rio
      if(request.readyState==4) {
      }
    }
  };

}

// Recupera a div que conter� a imagem
// e o span com o t�tulo de nosso arquivo
var area = document.getElementById("image-area");
var title = document.getElementById("title");

var fileFrameArea = new FileFrame(area, title);
fileFrameArea.init();