new Vue({
    el: '#app',
    data: {
      ativos: [],
      parados: [],
      imagens: [],
    },
    methods: {
      carregarContainers() {
        fetch('http://localhost:8080/acoes/list.php')
          .then(response => response.json())
          .then(data => {
            this.ativos = data.ativos;
            this.parados = data.parados;
          })
          .catch(error => {
            console.error('Erro ao carregar contêineres:', error);
            alert('Erro ao carregar os contêineres.');
          });
      },
      carregarImagens() {
        fetch('http://localhost:8080/acoes/listarimagens.php')
          .then(response => response.json())
          .then(data => {
            this.imagens = data;
          })
          .catch(error => {
            console.error('Erro ao carregar as imagens:', error);
            alert('Erro ao carregar as imagens.');
          });
      },
      startContainer(id) {
        fetch(`acoes/start.php?id=${id}`, { method: 'POST' })
          .then(() => this.carregarContainers())
          .catch(() => alert('Erro ao iniciar contêiner.'));
      },
      stopContainer(id) {
        fetch('acoes/stop.php', {
          method: 'POST',
          body: JSON.stringify({ id: id }),
          headers: {
            'Content-Type': 'application/json'
          }
        })
        .then(() => this.carregarContainers())
        .catch(() => alert('Erro ao parar o contêiner.'));
      },
      removeContainer(id) {
        fetch(`acoes/remove.php?id=${id}`, { method: 'DELETE' })
          .then(() => this.carregarContainers())
          .catch(() => alert('Erro ao remover contêiner.'));
      }
    },
    created() {
      this.carregarContainers();
      this.carregarImagens();
    }
  });
  