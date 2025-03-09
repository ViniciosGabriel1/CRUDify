import Swal from 'sweetalert2';  // Importando diretamente o Swal

export function useAlert() {
  return {
    success(message: string, title = "Sucesso!") {
      Swal.fire({
        title,
        text: message,
        icon: "success",
        confirmButtonText: "OK",
      });
    },

     displayErros(messages: string | string[] | Record<string, string>, title = "Erro!") {
      let messageArray: string[];
  
      if (typeof messages === "object" && !Array.isArray(messages)) {
          messageArray = Object.values(messages); // Extraímos os valores do objeto de erros
      } else {
          messageArray = Array.isArray(messages) ? messages : [messages]; // Garante que sempre será um array
      }
  
      const mensagemComQuebrasDeLinha = messageArray.join("\n");


      Swal.fire({
          title, 
          text:mensagemComQuebrasDeLinha, 
          icon: "error",
          confirmButtonText: "OK",
      });
  },

    confirm(action: () => void, message = "Você tem certeza?", title = "Confirmação") {
      Swal.fire({
        title,
        text: message,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sim, continuar!",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          action();
        }
      });
    },

    validationErrors(errors: Record<string, string[]>) {
      Swal.fire({
        title: "Erro de Validação",
        html: Object.values(errors)
          .map((error) => `<p>${error}</p>`)
          .join(""),
        icon: "error",
      });
    },
  };
}
