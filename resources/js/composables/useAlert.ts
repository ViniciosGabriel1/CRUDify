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

    error(message: string, title = "Erro!") {
      Swal.fire({
        title,
        text: message,
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
