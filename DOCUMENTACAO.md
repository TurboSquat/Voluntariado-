# Documentacao - Plataforma de Voluntariado Local

## 1. Apresentacao do Projeto

A **Plataforma de Voluntariado Local** e uma aplicacao web que permite a cidadãos e instituicoes da comunidade de Boticas gerir oportunidades de voluntariado. A plataforma facilita o registo de voluntarios, a publicacao de oportunidades pelas instituicoes e a gestao de inscricoes por um administrador.

### Objetivos
- Facilitar a ligacao entre voluntarios e instituicoes locais
- Gerir oportunidades de voluntariado de forma simples e transparente
- Garantir a seguranca e protecao dos dados pessoais (RGPD)
- Permitir ao administrador supervisionar toda a atividade da plataforma

### Publico-alvo
- Cidadãos que desejam voluntariar-se
- Instituicoes que precisam de voluntarios
- Administrador que gere a plataforma

---

## 2. Manual de Instalacao

### Requisitos
- Um navegador web moderno (Chrome, Firefox, Edge, Safari)
- Acesso à Internet
- Conta Google (para aceder ao Firebase Console, apenas para gestao tecnica)

### Passos de Instalacao

1. **Aceder à plataforma**
   - Abrir o navegador e aceder ao endereco: `https://turbosquat.github.io/Voluntariado-/`

2. **Dados de acesso iniciais (administrador)**
   - Email: `admin@voluntariado.pt`
   - Password: `admin123`
   - **IMPORTANTE**: Alterar a password apos o primeiro login

3. **Firebase (gestao tecnica)**
   - A plataforma utiliza Firebase Firestore como base de dados
   - Os dados sao guardados na colecao `dados` no Firestore
   - As regras de Firestore estao em modo de teste (permitem leitura e escrita)
   - Para alterar as regras, aceder ao Firebase Console: `https://console.firebase.google.com/project/voluntariado-29aa1/firestore/rules`

4. **Atualizacao da plataforma**
   - Para atualizar, basta substituir o ficheiro `index.html` no repositorio GitHub
   - O GitHub Pages atualiza automaticamente apos o commit

---

## 3. Manual de Utilizador

### 3.1 Utilizador Nao Registado
- **Visualizar oportunidades**: Pode ver todas as oportunidades publicadas na pagina principal
- **Ver detalhes**: Pode ver informacoes detalhadas de cada oportunidade
- **Registar-se**: Pode criar conta como voluntario ou instituicao

### 3.2 Voluntario

#### Registo
1. Aceder a "Registar" na barra de navegacao
2. Selecionar "Voluntario"
3. Preencher: nome, email, password, telefone, localidade, data de nascimento, disponibilidade, competencias
4. Selecionar interesses (categorias)
5. Aceitar termos e consentimento RGPD
6. Submeter

#### Funcionalidades
- **Perfil**: Ver e editar perfil pessoal
- **Oportunidades**: Explorar oportunidades por categoria, local ou data
- **Inscricao**: Inscrever-se em oportunidades abertas
- **Cancelar inscricao**: Remover inscricao (antes da conclusao)
- **Dashboard**: Ver resumo de inscricoes e atividade

### 3.3 Instituicao

#### Registo
1. Aceder a "Registar" na barra de navegacao
2. Selecionar "Instituicao"
3. Preencher: nome, NIF, email, password, tipo, morada, telefone, pessoa de contacto, descricao
4. Aceitar termos
5. Submeter

#### Funcionalidades
- **Aprovacao**: A conta precisa de aprovacao do administrador antes de publicar
- **Criar oportunidade**: Criar novas oportunidades de voluntariado
- **Gerir oportunidades**: Editar, concluir ou eliminar oportunidades
- **Ver inscritos**: Consultar voluntarios inscritos em cada oportunidade
- **Perfil**: Editar dados da instituicao

### 3.4 Administrador

#### Login
1. Aceder a "Login"
2. Introduzir email e password do administrador

#### Funcionalidades
- **Dashboard**: Estatisticas gerais (oportunidades ativas, concluidas, aceites, inscricoes)
- **Instituicoes pendentes**: Aprovar ou rejeitar novas instituicoes
- **Oportunidades concluidas**: Aceitar ou rejeitar conclusao de oportunidades
- **Inscricoes**: Gerir inscricoes (aprovar/rejeitar)
- **Categorias**: Adicionar ou eliminar categorias
- **Utilizadores**: Ver e eliminar utilizadores
- **Relatorios**: Estatisticas detalhadas, inscricoes por categoria, atividade recente

---

## 4. Documentacao Tecnica

### 4.1 Arquitetura
- **Tipo**: Aplicacao web SPA (Single Page Application) de ficheiro unico
- **Frontend**: HTML5 + CSS3 + Bootstrap 5 + JavaScript vanilla
- **Backend**: Firebase Firestore (Base de dados como servico)
- **Autenticacao**: Custom (email + password com hash SHA-256)
- **Hosting**: GitHub Pages

### 4.2 Estrutura do Ficheiro
O projeto e composto por um unico ficheiro `index.html` que contem:
- HTML (estrutura da pagina)
- CSS (estilos inline)
- JavaScript (toda a logica da aplicacao)

### 4.3 Base de Dados (Firestore)
Colecao principal: `dados`

| Documento | Descricao |
|-----------|-----------|
| `users` | Utilizadores da plataforma (admin, voluntarios, instituicoes) |
| `voluntarios` | Perfis de voluntarios (dados complementares ao user) |
| `instituicoes` | Perfis de instituicoes (dados complementares ao user) |
| `oportunidades` | Oportunidades de voluntariado publicadas |
| `inscricoes` | Inscricoes de voluntarios em oportunidades |
| `categorias` | Categorias de oportunidades |
| `registosAtividade` | Logs de atividade da plataforma |

### 4.4 Funcoes Principais

#### Gestao de Dados
- `getData(chave)` - Le dados do cache local
- `setData(chave, dados)` - Guarda dados no cache e no Firestore
- `loadAllData()` - Carrega todos os dados do Firestore
- `loadAllDataWithListeners()` - Carrega dados e ativa listeners em tempo real

#### Autenticacao
- `hashPassword(password)` - Gera hash SHA-256 da password
- `fazerLogin(email, password)` - Autentica o utilizador
- `fazerRegisto(dados)` - Regista novo utilizador
- `fazerLogout()` - Termina sessao

#### Gestao de Contas
- `aprovarInst(id)` - Aprova instituicao
- `rejeitarInst(id)` - Rejeita instituicao
- `eliminarUtilizador(uid)` - Elimina utilizador e dados associados

#### Gestao de Oportunidades
- `guardarNovaOportunidade()` - Cria nova oportunidade
- `editarOportunidade(id)` - Edita oportunidade existente
- `eliminarOportunidade(id)` - Elimina oportunidade
- `concluirOportunidade(id)` - Marca oportunidade como concluida

#### Gestao de Inscricoes
- `inscrever(opId)` - Voluntario inscreve-se numa oportunidade
- `desinscrever(opId)` - Voluntario cancela inscricao
- `aprovarInsc(id)` - Admin aprova inscricao
- `rejeitarInsc(id)` - Admin rejeita inscricao

#### Seguranca
- `resetFirestore()` - Limpa todos os dados (apenas admin)
- `initDB()` - Inicializa dados apenas na primeira vez

### 4.5 Sincronizacao em Tempo Real
A plataforma utiliza `onSnapshot` do Firebase para detetar alteracoes em tempo real. Quando qualquer dados e alterado (localmente ou por outro browser), a interface atualiza automaticamente.

### 4.6 Permissoes por Perfil

| Perfil | Acoes Disponiveis |
|--------|-------------------|
| Administrador | Gerir tudo (instituicoes, oportunidades, inscricoes, categorias, utilizadores, relatorios) |
| Instituicao | Criar/editar/eliminar oportunidades proprias, ver inscritos |
| Voluntario | Inscrever-se/cancelar inscricoes, gerir perfil |

---

## 5. Fluxos Principais

### 5.1 Registo de Voluntario
1. Voluntario preenche formulario de registo
2. Dados validados (campos obrigatorios, email unico, password segura)
3. Password hasheada com SHA-256
4. Conta criada em `users` e perfil em `voluntarios`
5. Consentimento RGPD registado com timestamp
6. Voluntario redirecionado para a home page

### 5.2 Registo de Instituicao
1. Instituicao preenche formulario de registo
2. Dados validados (NIF 9 digitos, email unico, campos obrigatorios)
3. Conta criada com estado `pendente`
4. Administrador notificado (via painel admin)
5. Administrador aprova ou rejeita

### 5.3 Publicacao de Oportunidade
1. Instituicao aprovada cria oportunidade
2. Preenche titulo, descricao, local, datas, vagas, categoria
3. Oportunidade fica com estado `inscricoes_abertas`
4. Voluntarios podem ver e inscrever-se

### 5.4 Inscricao de Voluntario
1. Voluntario seleciona oportunidade
2. Preenche telefone e mensagem de motivacao
3. Sistema valida: vagas disponiveis, nao duplicada, oportunidade ativa
4. Inscricao criada com estado `pendente`
5. Admin ou instituicao pode aprovar/rejeitar

### 5.5 Conclusao de Oportunidade
1. Instituicao marca oportunidade como concluida
2. Administrador recebe notificacao no painel
3. Administrador aceita ou rejeita a conclusao
4. Se aceite, oportunidade fica com estado `aceite`

---

## 6. Criterios de Avaliacao Cumpridos

### 19.1 Funcionalidade
- A aplicacao permite registar voluntarios
- A aplicacao permite registar instituicoes
- As instituicoes conseguem publicar oportunidades
- Os voluntarios conseguem inscrever-se
- O administrador consegue gerir dados principais

### 19.2 Qualidade Tecnica
- Codigo organizado com funcoes separadas por responsabilidade
- Base de dados Firestore bem modelada (7 colecoes)
- Separacao de permissoes por perfil (admin/instituicao/voluntario)
- Validacao dos formularios (campos obrigatorios, email, NIF, passwords)
- Tratamento de erros (mensagens de erro no form, alerts, catch no Firestore)
- Utilizacao correta do Bootstrap 5 como framework CSS

### 19.3 Usabilidade
- Interface simples e clean com Bootstrap
- Navegacao intuitiva (barra de navegacao, tabs, botoes)
- Paginas responsivas (media queries para mobile)
- Mensagens claras (alerts, badges, textos informativos)
- Facil de usar por cidadaos e instituicoes

### 19.4 Seguranca e Protecao de Dados
- Autenticacao implementada (email + password)
- Passwords protegidas com hash SHA-256 (crypto.subtle)
- Permissoes por perfil (verificadas em todas as funcoes)
- Dados pessoais nao expostos publicamente (requer login)
- Consentimento RGPD registado (checkbox + timestamp)
- Area administrativa protegida (verificacao de perfil)

### 19.5 Documentacao
- Manual de instalacao (Secao 2)
- Manual de utilizador (Secao 3)
- Documentacao tecnica (Secao 4)
- Apresentacao clara do projeto (Secao 1)
- Identificacao de limitacoes e melhorias (Secao 7)

---

## 7. Limitacoes e Melhorias Futuras

### Limitacoes Atuais
1. **Autenticacao simples**: Passwords comparadas com hash mas sem sistema de recuperacao
2. **Sem notificacoes por email**: Os utilizadores nao recebem emails de notificacao
3. **Sem upload de imagens**: As oportunidades nao podem ter fotos
4. **Sem sistema de avaliacao**: Nao ha avaliacao pos-atividade de voluntarios
5. **Firestore em modo de teste**: As regras permitem leitura/escrita sem restricoes (expira Aug 2026)
6. **Ficheiro unico**: Todo o codigo num so ficheiro, dificulta manutencao
7. **Sem HTTPS proprio**: Depende do GitHub Pages para HTTPS
8. **Sem backup automatico**: Nao ha sistema de backup dos dados
9. **Sem gestao de vagas automatica**: As vagas nao diminuem automaticamente com inscricoes aprovadas
10. **Sem sistema de search**: Nao ha barra de pesquisa de oportunidades

### Melhorias Futuras Sugeridas
1. **Recuperacao de password**: Sistema de email para redefinir password
2. **Notificacoes por email**: Enviar emails quando ha novas inscricoes ou decisoes
3. **Upload de imagens**: Adicionar fotos às oportunidades
4. **Sistema de avaliacao**: Avaliacao pos-atividade com notas e comentarios
5. **Regras Firestore**: Configurar regras de seguranca em producao
6. **Refatorizacao**: Dividir em multiplos ficheiros (HTML, CSS, JS separados)
7. **PWA**: Tornar a plataforma instalavel no telemovel
8. **Dashboard analytics**: Graficos interativos de atividade
9. **Exportar dados**: Funcionalidade para exportar inscricoes em CSV/PDF
10. **Multi-idioma**: Suporte para portugues e ingles

---

## 8. Produto Minimo Viavel — MVP

O MVP da plataforma inclui obrigatoriamente:

| Funcionalidade | Estado |
|----------------|--------|
| Login/Autenticacao | Feito (email + SHA-256) |
| Registo de voluntario | Feito (com RGPD) |
| Registo de instituicao | Feito (com aprovacao admin) |
| Publicacao de oportunidades | Feito (pela instituicao) |
| Inscricao de voluntarios | Feito (com validacao) |
| Gestao pelo admin | Feito (aprovar, rejeitar, gerir) |

---

## 9. Riscos do Projeto

| Risco | Descricao | Mitigacao |
|-------|-----------|-----------|
| Excesso de funcionalidades | Tentar implementar demasiadas funcionalidades e nao concluir o essencial | Focar no MVP; implementar melhorias futuras apos a entrega |
| Dificuldade na gestao de permissoes | Instituicoes verem dados que nao devem | Testar cuidadosamente permissoes por perfil em todos os cenarios |
| Dados pessoais | Exposicao indevida de dados de voluntarios | Aplicar regras de protecao de dados desde o inicio (RGPD, hash passwords) |
| Interface complexa | Plataforma dificil de usar por cidadaos comuns | Manter interface simples com Bootstrap; testar com utilizadores reais |
| Dependencia de servico externo | Firebase pode ter indisponibilidade temporaria | Cache local (localStorage) funciona como backup; dados persistem no browser |
| Seguranca das regras Firestore | Regras em modo de teste permitem acesso irrestrito | Configurar regras de seguranca em producao apos validacao |

---

## 10. Divisao de Trabalho Sugerida

| Area | Responsabilidades |
|------|-------------------|
| **Backend/Firebase** | Configuracao Firestore, regras de seguranca, estrutura de dados, sincronizacao em tempo real |
| **Frontend/Interface** | HTML/CSS/Bootstrap, formularios, navegacao, design responsivo, experiencia do utilizador |
| **Admin/Relatorios** | Painel de administracao, aprovacoes, estatisticas, relatorios, gestao de categorias |
| **Testes/Documentacao** | Testes em varios browsers, documentacao de utilizador, manual de instalacao, apresentacao final |

---

## 11. Requisitos de Demonstracao Final

Na apresentacao final, o grupo devera demonstrar:

1. Registo de um voluntario
2. Registo ou aprovacao de uma instituicao
3. Criacao de uma oportunidade
4. Pesquisa de oportunidades
5. Inscricao do voluntario
6. Aceitacao da inscricao pela instituicao
7. Consulta pelo administrador
8. Relatorio simples de atividade

---

## 12. Conclusao

A Plataforma de Voluntariado Local e um projeto adequado para estagiarios porque combina desenvolvimento web, bases de dados, autenticacao, permissoes, protecao de dados, usabilidade e impacto social.

Ao mesmo tempo, e uma solucao com utilidade pratica para ounicipio e para as instituicoes locais, podendo contribuir para reforcara participacao civica, a organizacao comunitaria e a aproximacao entre cidadaos e entidades que necessitam de apoio voluntario.
