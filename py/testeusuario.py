from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.ui import Select
from faker import Faker
import time

# Configurações do navegador
options = webdriver.ChromeOptions()
options.add_argument("--disable-notifications")
options.add_argument("--disable-infobars")
options.add_argument("--disable-extensions")

driver = webdriver.Chrome(options=options)
wait = WebDriverWait(driver, 10)
fake = Faker("pt_BR")

# ====== LOGIN ======
driver.get("http://localhost:8080/sa-modelagem/login.php")
print("🌐 Acessando página de login...")
time.sleep(3)

# Email
campo_email = wait.until(EC.presence_of_element_located((By.ID, "email")))
campo_email.send_keys("admin@admin")
print(f"[LOGIN] Email digitado: admin@admin")
time.sleep(2)

# Senha
campo_senha = driver.find_element(By.ID, "senha")
campo_senha.send_keys("123")
print(f"[LOGIN] Senha digitada: 123")
time.sleep(2)

# Entrar
print("👉 Clicando no botão de login...")
driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
print("✅ Login realizado com sucesso!")
time.sleep(3)

# ====== IR PARA PÁGINA DE USUÁRIOS ======
print("👉 Indo para a tela de Gerenciar Usuários...")
link_usuarios = wait.until(
    EC.element_to_be_clickable((By.CSS_SELECTOR, ".sidebar .menu li a[href='usuarios.php']"))
)
link_usuarios.click()
print("✅ Página de Usuários aberta!")
time.sleep(3)

# ====== PREENCHER FORMULÁRIO DE CADASTRO DE USUÁRIO ======
print("👉 Cadastrando um novo USUÁRIO...")

# Nome - usando seletor mais específico
nome = fake.name()
campo_nome = wait.until(EC.visibility_of_element_located((By.CSS_SELECTOR, ".form-adicionar input[name='nome']")))
campo_nome.clear()
campo_nome.send_keys(nome)
print(f"[USUÁRIO] Nome digitado: {nome}")
time.sleep(2)

# Email
email = fake.email()
campo_email = driver.find_element(By.CSS_SELECTOR, ".form-adicionar input[name='email']")
campo_email.clear()
campo_email.send_keys(email)
print(f"[USUÁRIO] Email digitado: {email}")
time.sleep(2)

# Senha
senha = "senha123"
campo_senha = driver.find_element(By.CSS_SELECTOR, ".form-adicionar input[name='senha']")
campo_senha.clear()
campo_senha.send_keys(senha)
print(f"[USUÁRIO] Senha digitada: {senha}")
time.sleep(2)

# Selecionar cargo como "Atendente"
select_cargo = Select(driver.find_element(By.CSS_SELECTOR, ".form-adicionar select[name='cargo']"))
select_cargo.select_by_value("Atendente")
print("[USUÁRIO] Cargo selecionado: Atendente")
time.sleep(2)

# Verificar se o checkbox está marcado (ativo por padrão)
checkbox_ativo = driver.find_element(By.CSS_SELECTOR, ".form-adicionar input[name='ativo']")
if not checkbox_ativo.is_selected():
    checkbox_ativo.click()
    print("[USUÁRIO] Status: Ativo")
else:
    print("[USUÁRIO] Status: Ativo (já estava selecionado)")
time.sleep(2)

# ====== SALVAR ======
print("👉 Clicando no botão ADICIONAR...")
botao_adicionar = driver.find_element(By.CSS_SELECTOR, ".form-adicionar button[type='submit']")
botao_adicionar.click()
print("✅ Usuário cadastrado com sucesso!")

time.sleep(5)
print("✅ Script finalizado com sucesso!")

time.sleep(3)
driver.quit()  