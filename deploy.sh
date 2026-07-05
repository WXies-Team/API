#!/bin/bash
set -e

export PATH="/usr/local/bin:/usr/bin:/bin:$PATH"
export HOME="${HOME:-/root}"

PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"
cd "${PROJECT_DIR}"

REMOTE_BRANCH="origin/main"
LOCK_FILE="/tmp/deploy-$(basename "${PROJECT_DIR}").lock"
SCRIPT_HASH=$(md5sum "$0" | awk '{print $1}')

# 防止重复运行
cleanup() { rm -f "${LOCK_FILE}"; }
trap cleanup EXIT

if [ -f "${LOCK_FILE}" ]; then
    pid=$(cat "${LOCK_FILE}")
    if kill -0 "${pid}" 2>/dev/null; then
        echo ">>> 已有实例运行中 (PID: ${pid})，退出"
        exit 0
    fi
fi
echo $$ > "${LOCK_FILE}"

deploy() {
    echo ">>> $(date '+%Y-%m-%d %H:%M:%S') 开始检查部署"
    
    if ! gh auth status &>/dev/null; then
        echo ">>> 未登录 gh，请先执行 gh auth login" >&2
        return 1
    fi
    
    local max_retry=3
    for i in $(seq 1 "${max_retry}"); do
        echo ">>> 检查远程更新（第 ${i} 次）..."
        if git fetch origin main 2>/dev/null; then
            break
        fi
        if [[ ${i} -eq ${max_retry} ]]; then
            echo ">>> fetch 失败，已重试 ${max_retry} 次，跳过本次检查" >&2
            return 1
        fi
        echo ">>> fetch 失败，${i}s 后重试..."
        sleep "${i}"
    done
    
    local local_rev remote_rev
    local_rev=$(git rev-parse HEAD)
    remote_rev=$(git rev-parse "${REMOTE_BRANCH}")
    
    if [[ "${local_rev}" == "${remote_rev}" ]]; then
        echo ">>> 无新提交，跳过部署"
        return 0
    fi
    
    echo ">>> 发现新提交，同步代码..."
    git reset --hard "${REMOTE_BRANCH}"
    
    # 检查脚本自身是否更新，若有则重启
    local new_hash
    new_hash=$(md5sum "$0" | awk '{print $1}')
    if [[ "${new_hash}" != "${SCRIPT_HASH}" ]]; then
        echo ">>> 脚本已更新，重启中..."
        rm -f "${LOCK_FILE}"
        exec "$0"
    fi
    
    echo ">>> 部署完成 $(date '+%Y-%m-%d %H:%M:%S')"
}

while true; do
    deploy || true
    sleep 60
done
