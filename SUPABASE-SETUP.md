# Configuração do Supabase para MangApp

## Passos para configurar o Supabase

### 1. Acessar o Dashboard do Supabase
- Acesse: https://yeceuxmjmsxovjucdnce.supabase.co
- Faça login na sua conta do Supabase

### 2. Executar o Schema SQL
1. No dashboard do Supabase, vá para **SQL Editor**
2. Clique em **New Query**
3. Copie e cole o conteúdo do arquivo `supabase-schema.sql`
4. Clique em **Run** para executar o script

### 3. Configurar Autenticação
1. Vá para **Authentication** > **Settings**
2. Configure as seguintes opções:
   - **Site URL**: `http://localhost/Magapp` (ou sua URL de desenvolvimento)
   - **Redirect URLs**: Adicione `http://localhost/Magapp/dashboard.php`
   - **Email Confirmation**: Ative se desejar confirmação por email
   - **Password Requirements**: Configure conforme necessário

### 4. Configurar Políticas de Segurança
As políticas RLS (Row Level Security) já estão configuradas no schema SQL:
- Usuários só podem ver seus próprios dados
- Usuários só podem atualizar seus próprios dados
- Novos usuários podem inserir seus próprios dados

### 5. Testar a Integração
1. **Teste de conexão**: Acesse `http://localhost/Magapp/test-supabase.php` para verificar se a conexão está funcionando
2. **Registro**: Acesse `http://localhost/Magapp/register.php` e crie uma nova conta
3. **Login**: Faça login em `http://localhost/Magapp/login.php`
4. **Logout**: Teste o logout

## Estrutura da Tabela de Usuários

A tabela `users` contém os seguintes campos:
- `id`: UUID único do usuário
- `email`: Email do usuário (único)
- `username`: Nome de usuário (único)
- `created_at`: Data de criação da conta
- `updated_at`: Data da última atualização
- `last_login`: Data do último login
- `is_active`: Status ativo/inativo da conta

## Funcionalidades Implementadas

### Autenticação
- ✅ Login com email e senha
- ✅ Registro de novos usuários
- ✅ Logout seguro
- ✅ Verificação de sessão ativa
- ✅ Validação de credenciais

### Segurança
- ✅ Row Level Security (RLS) habilitado
- ✅ Políticas de acesso configuradas
- ✅ Validação de dados de entrada
- ✅ Sanitização de inputs

### Integração
- ✅ Cliente Supabase configurado
- ✅ Funções helper para autenticação
- ✅ Tratamento de erros
- ✅ Logs de erro para debug

## Arquivos Modificados

1. `config-supabase.php` - Configuração e funções do Supabase
2. `login.php` - Integração com Supabase Auth
3. `register.php` - Registro de usuários via Supabase
4. `logout.php` - Logout via Supabase
5. `supabase-schema.sql` - Schema da tabela de usuários

## Próximos Passos

1. Execute o schema SQL no Supabase
2. Configure as URLs de redirecionamento
3. Teste o sistema de autenticação
4. Configure confirmação por email (opcional)
5. Implemente recuperação de senha (opcional)
