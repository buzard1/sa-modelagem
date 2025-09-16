from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time, random

# Configurações do navegador
options = webdriver.ChromeOptions()
options.add_argument("--disable-notifications")
options.add_argument("--disable-infobars")
options.add_argument("--disable-extensions")

driver = webdriver.Chrome(options=options)
wait = WebDriverWait(driver, 10)

# ====== LOGIN ======
driver.get("http://localhost:8080/sa-modelagem/index.php")
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

# ====== IR PARA CADASTRO DE ORDEM ======
print("👉 Indo para a tela de Cadastro de Ordem de Serviço...")
link_ordem = wait.until(
    EC.element_to_be_clickable((By.CSS_SELECTOR, ".sidebar .menu li a[href='cadastro-ordem_serv.php']"))
)
link_ordem.click()
print("✅ Página de Cadastro de Ordem aberta!")
time.sleep(3)

# ====== PREENCHER FORMULÁRIO ======
# Lista de CPFs existentes na tabela cliente
cpfs_existentes = [
    '039.754.826-56', '064.837.251-08', '079.561.832-86', '084.135.679-39',
    '093.785.241-41', '104.598.637-20', '124.798.560-11', '145.069.237-07',
    '145.736.982-64', '150.692.738-68', '204.398.165-05', '209.165.478-76',
    '230.476.519-06', '231.754.680-71', '234.785.601-44', '278.905.641-20',
    '289.036.475-56', '315.428.906-24', '320.856.497-00', '354.609.217-16',
    '371.098.645-10', '381.257.609-03', '392.618.745-09', '397.650.421-16',
    '403.672.895-47', '407.962.831-50', '471.839.562-37', '483.520.769-65',
    '492.165.837-46', '507.324.961-52', '569.047.281-67', '578.120.649-30',
    '597.840.312-05', '621.987.543-55', '625.948.371-64', '647.210.538-35',
    '648.253.791-09', '670.123.859-95', '675.829.430-74', '678.943.012-13',
    '680.975.314-10', '702.164.598-85', '705.291.684-30', '718.203.596-03',
    '723.590.481-04', '751.893.046-39', '751.948.620-67', '760.284.519-67',
    '803.642.751-62', '809.576.421-30', '824.076.519-01', '831.276.405-44',
    '831.549.720-05', '854.170.263-44', '123.123.123-12'
]

# Selecionar um CPF existente aleatoriamente
cpf = random.choice(cpfs_existentes)
aparelho = random.choice(["iPhone 11", "Samsung Galaxy S20", "Motorola G9", "Xiaomi Redmi Note 9"])
problema = random.choice(["Tela quebrada", "Bateria não carrega", "Sistema não inicia", "Botão power falhando"])
servico = random.choice(["Troca de tela", "Troca de bateria", "Reinstalação de sistema", "Troca de botão"])
valor = round(random.uniform(100, 500), 2)
pagamento = random.choice(["Pix", "Dinheiro", "Cartão", "Boleto"])
status = random.choice(["pendente", "em-andamento", "concluido"])

campo_cpf = wait.until(EC.visibility_of_element_located((By.ID, "cpfcliente")))
campo_cpf.send_keys(cpf)
print(f"[ORDEM] CPF digitado: {cpf}")
time.sleep(2)

campo_aparelho = driver.find_element(By.ID, "aparelho")
campo_aparelho.send_keys(aparelho)
print(f"[ORDEM] Aparelho digitado: {aparelho}")
time.sleep(2)

campo_problema = driver.find_element(By.ID, "problema")
campo_problema.send_keys(problema)
print(f"[ORDEM] Problema digitado: {problema}")
time.sleep(2)

campo_servico = driver.find_element(By.ID, "servico")
campo_servico.send_keys(servico)
print(f"[ORDEM] Serviço digitado: {servico}")
time.sleep(2)

campo_valor = driver.find_element(By.ID, "valor")
campo_valor.send_keys(str(valor))
print(f"[ORDEM] Valor digitado: R$ {valor}")
time.sleep(2)

campo_pagamento = driver.find_element(By.ID, "Pagamento")
campo_pagamento.send_keys(pagamento)
print(f"[ORDEM] Forma de pagamento escolhida: {pagamento}")
time.sleep(2)

campo_status = driver.find_element(By.ID, "status")
campo_status.send_keys(status)
print(f"[ORDEM] Status escolhido: {status}")
time.sleep(2)

# ====== SALVAR ======
print("👉 Clicando no botão SALVAR ORDEM...")
driver.find_element(By.CSS_SELECTOR, "form button[type='submit']").click()
print("✅ Ordem de Serviço cadastrada com sucesso!")

time.sleep(5)
driver.quit()